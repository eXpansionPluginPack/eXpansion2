<?php

namespace eXpansion\Bundle\Maps\Plugins;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\Maps\Structure\MxInfo;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use Maniaplanet\DedicatedServer\Connection;

class ManiaExchange implements ListenerInterfaceExpApplication
{

    const SITE_TM = "TM";
    const SITE_SM = "SM";

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var ChatNotification
     */
    private $chatNotification;
    /**
     * @var Http
     */
    private $http;
    /**
     * @var AdminGroups
     */
    private $adminGroups;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    /**
     * @var Console
     */
    private $console;
    /**
     * @var JukeboxService
     */
    private $jukebox;


    /**
     * ManiaExchange constructor.
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param Http $http
     * @param AdminGroups $adminGroups
     * @param GameDataStorage $gameDataStorage
     * @param Console $console
     * @param JukeboxService $jukebox
     */
    public function __construct(
        Connection $connection,
        ChatNotification $chatNotification,
        Http $http,
        AdminGroups $adminGroups,
        GameDataStorage $gameDataStorage,
        Console $console,
        JukeboxService $jukebox
    ) {
        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
        $this->http = $http;
        $this->adminGroups = $adminGroups;
        $this->gameDataStorage = $gameDataStorage;
        $this->console = $console;
        $this->jukebox = $jukebox;
    }

    /**
     * @param string $login
     * @param integer $id
     * @param string $mxsite "TM" or "SM"
     */
    public function addMap($login, $id, $mxsite)
    {

        if (!$this->adminGroups->hasPermission($login, "mania_exchange.add")) {
            $this->chatNotification->sendMessage('expansion_mx.chat.nopermission', $login);

            return;
        }
        $options = [
            CURLOPT_HTTPHEADER => [
                "Content-Type" => "application/json",
                "X-ManiaPlanet-ServerLogin" => $this->gameDataStorage->getSystemInfo()->serverLogin,
            ],
        ];

        if (!$mxsite) {
            $mxsite = "TM";
        }

        $group = $this->adminGroups->getLoginUserGroups($login);

        $this->chatNotification->sendMessage(
            'expansion_mx.chat.start',
            $group,
            ["%id%" => $id, "%site%" => $mxsite]
        );

        $this->http->get("https://api.mania-exchange.com/".strtolower($mxsite)."/maps?ids=".$id,
            [$this, 'callbackAddMap1'],
            ['login' => $login, 'site' => $mxsite, 'mxId' => $id], $options);

    }


    public function callbackAddMap1(HttpResult $result)
    {
        $additionalData = $result->getAdditionalData();
        $group = $this->adminGroups->getLoginUserGroups($additionalData['login']);

        $json = json_decode($result->getResponse(), true);
        if (isset($json['StatusCode'])) {
            $this->chatNotification->sendMessage(
                'expansion_mx.chat.apierror',
                $group,
                ["%status%" => $json['StatusCode'], "%message%" => $json['Message']]
            );

            return;
        }

        $mxInfo = new MxInfo($json);
        $additionalData['mxInfo'] = $mxInfo;

        if (!$result->hasError()) {
            $options = [
                CURLOPT_HTTPHEADER => [
                    "Content-Type" => "application/json",
                    "X-ManiaPlanet-ServerLogin" => $this->gameDataStorage->getSystemInfo()->serverLogin,
                ],
            ];

            $this->http->get("https://".strtolower($additionalData['site']).
                ".mania-exchange.com/tracks/download/".$additionalData['mxId'],
                [$this, 'callbackAddMap2'],
                $additionalData, $options);
        } else {
            $this->chatNotification->sendMessage(
                'expansion_mx.chat.httperror',
                $group,
                ["%status%" => $result->getHttpCode(), "%message%" => $result->getError()]
            );
        }
    }

    public function callbackAddMap2(HttpResult $result)
    {
        $data = $result->getAdditionalData();
        $group = $this->adminGroups->getLoginUserGroups($data['login']);

        if ($result->hasError()) {
            $this->chatNotification->sendMessage(
                'expansion_mx.chat.httperror',
                $group,
                ["%status%" => $result->getHttpCode(), "%message%" => $result->getError()]
            );

            return;
        }

        /** @var MxInfo $info */
        $info = $data['mxInfo'];
        $authorName = $this->cleanString($info->Username);
        $mapName = $this->cleanString(
            trim(
                mb_convert_encoding(
                    substr(TMString::trimStyles($info->GbxMapName), 0, 20),
                    "7bit",
                    "UTF-8"
                )
            )
        );

        $filename = $data['mxId']."-".$authorName."-".$mapName.".Map.Gbx";
        try {
            // @todo write mx info from map to database!

            $this->connection->writeFile($filename, $result->getResponse());
            $this->connection->addMap($filename);

            $map = $this->connection->getMapInfo($filename);
            $this->jukebox->addMap($map, $data['login']);
            $this->chatNotification->sendMessage(
                'expansion_mx.chat.success',
                null,
                [
                    "%mxid%" => $data['mxId'],
                    "%mapauthor%" => $map->author,
                    "%mapname%" => TMString::trimControls($map->name),
                ]
            );
        } catch (\Exception $e) {
            $this->chatNotification->sendMessage(
                'expansion_mx.chat.dedicatedexception',
                $group,
                [
                    "%message%" => $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Remove special characters from map name
     *
     * @param $string
     * @return mixed
     */
    protected function cleanString($string)
    {
        return str_replace(array("/", "\\", ":", ".", "?", "*", '"', "|", "<", ">", "'"), "", $string);
    }


    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // TODO: Implement onApplicationInit() method.
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {

    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // TODO: Implement onApplicationStop() method.
    }
}
