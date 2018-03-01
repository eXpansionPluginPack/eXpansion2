<?php

namespace eXpansion\Bundle\Maps\Plugins;

use eXpansion\Bundle\Maps\Model\Map;
use eXpansion\Bundle\Maps\Model\MapQuery;
use eXpansion\Bundle\Maps\Model\Mxmap;
use eXpansion\Bundle\Maps\Model\MxmapQuery;
use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\Maps\Structure\MxInfo;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\FileSystem;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Helpers\Structures\HttpResult;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map as DedicatedMap;
use Propel\Runtime\Map\TableMap;
use Psr\Log\LoggerInterface;

class ManiaExchange implements ListenerInterfaceExpApplication
{
    const SITE_TM = "TM";
    const SITE_SM = "SM";

    /** @var bool */
    private $downloadProgressing = false;

    /**
     * @var Factory
     */
    private $factory;
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var JukeboxService
     */
    private $jukebox;

    /**
     * @var FileSystem
     */
    protected $fileSystem;


    protected $addQueue = [];

    /**
     * ManiaExchange constructor.
     *
     * @param Factory $factory
     * @param ChatNotification $chatNotification
     * @param Http $http
     * @param AdminGroups $adminGroups
     * @param GameDataStorage $gameDataStorage
     * @param Console $console
     * @param LoggerInterface $logger
     * @param JukeboxService $jukebox
     * @param FileSystem $fileSystem
     */
    public function __construct(
        Factory $factory,
        ChatNotification $chatNotification,
        Http $http,
        AdminGroups $adminGroups,
        GameDataStorage $gameDataStorage,
        Console $console,
        LoggerInterface $logger,
        JukeboxService $jukebox,
        FileSystem $fileSystem
    ) {
        $this->factory = $factory;
        $this->chatNotification = $chatNotification;
        $this->http = $http;
        $this->adminGroups = $adminGroups;
        $this->gameDataStorage = $gameDataStorage;
        $this->console = $console;
        $this->jukebox = $jukebox;
        $this->fileSystem = $fileSystem;
    }

    /** @var Mxmap[] $maps */
    public function addAllMaps($login, $maps)
    {
        if (!$this->adminGroups->hasPermission($login, "maps.add")) {
            $this->chatNotification->sendMessage('expansion_mx.chat.nopermission', $login);

            return;
        }

        $this->addQueue = $maps;
        // TODO replace with chat notification.
        $this->factory->getConnection()->chatSendServerMessage("Starting download. Maps in queue: ".count($this->addQueue));
        $map = array_shift($this->addQueue);
        $this->addMap($login, $map['mxid'], $map['mxsite']);
    }

    /**
     * add map to queue
     * @param string  $login
     * @param integer $id
     * @param string  $mxsite "TM" or "SM"
     */
    public function addMapToQueue($login, $id, $mxsite)
    {

        if (!$this->adminGroups->hasPermission($login, "maps.add")) {
            $this->chatNotification->sendMessage('expansion_mx.chat.nopermission', $login);

            return;
        }

        if ($this->downloadProgressing || count($this->addQueue) > 1) {

            $this->addQueue[] = ['mxid' => $id, 'mxsite' => $mxsite];
            $this->chatNotification->sendMessage("|info| Adding map to download queue...", $login);

            return;
        } else {
            $this->addMap($login, $id, $mxsite);
        }

    }

    /**
     * @param $login
     * @param $id
     * @param $mxsite
     */
    public function addMap($login, $id, $mxsite)
    {

        $options = [
            CURLOPT_HTTPHEADER => [
                "Content-Type" => "application/json",
                "X-ManiaPlanet-ServerLogin" => $this->gameDataStorage->getSystemInfo()->serverLogin,
            ],
        ];

        if (!$mxsite) {
            $mxsite = $this->gameDataStorage->getTitle();
        }

        $group = $this->adminGroups->getLoginUserGroups($login);

        $this->chatNotification->sendMessage(
            'expansion_mx.chat.start',
            $group,
            ["%id%" => $id, "%site%" => $mxsite]
        );
        $this->downloadProgressing = true;
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
            $this->downloadProgressing = false;

            return;
        }
        if (!isset($json[0])) {
            $this->chatNotification->sendMessage(
                'expansion_mx.chat.json',
                $group,
                ["%message%" => "Can't find info structure."]
            );

            return;
        }

        $mxInfo = new MxInfo($json[0]);
        $additionalData['mxInfo'] = $mxInfo;

        if (!$result->hasError()) {
            $options = [
                CURLOPT_FOLLOWLOCATION => false,
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

            if ($result->getHttpCode() == 302) {
                $this->chatNotification->sendMessage(
                    'expansion_mx.chat.decline',
                    $group,
                    []
                );
                $this->downloadProgressing = false;

                return;
            }

            $this->chatNotification->sendMessage(
                'expansion_mx.chat.httperror',
                $group,
                ["%status%" => $result->getHttpCode(), "%message%" => $result->getError()]
            );

            $this->downloadProgressing = false;

            return;
        }

        /** @var MxInfo $info */
        $info = $data['mxInfo'];

        $authorName = $this->cleanString($info->username);
        $mapName = $this->cleanString(
            trim(
                mb_convert_encoding(
                    substr(TMString::trimStyles($info->gbxMapName), 0, 20),
                    "7bit",
                    "UTF-8"
                )
            )
        );

        $filename = $data['mxId']."-".$authorName."-".$mapName.".Map.Gbx";

        try {
            $titlepack = $this->gameDataStorage->getSystemInfo()->titleId;
            $fileSystem = $this->fileSystem->getUserData();
            $dir = 'Maps'.DIRECTORY_SEPARATOR.$titlepack;
            $file = $dir.DIRECTORY_SEPARATOR.$filename;

            if (!$fileSystem->createDir($dir)) {
                $this->console->writeln('<error>Error while adding map!</error>');

                return;
            }

            if (!$fileSystem->has($file)) {
                $fileSystem->write($file, $result->getResponse());
            }

                if (!$this->factory->getConnection()->checkMapForCurrentServerParams($titlepack.DIRECTORY_SEPARATOR.$filename)) {
                    $this->chatNotification->sendMessage("expansion_mx.chat.fail");

                    return;
                }

            $map = $this->factory->getConnection()->getMapInfo($titlepack.DIRECTORY_SEPARATOR.$filename);
            $this->factory->getConnection()->addMap($map->fileName);

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

            $this->persistMapData($map, $info);
            $this->downloadProgressing = false;
        } catch (\Exception $e) {
            $this->chatNotification->sendMessage(
                'expansion_mx.chat.dedicatedexception',
                $group, ["%message%" => $e->getMessage()]
            );
            //  $this->logger->alert("Error while adding map : ".$e->getMessage(), ['exception' => $e]);
            $this->downloadProgressing = false;
        }

        if (count($this->addQueue) > 0) {
            $map = array_shift($this->addQueue);
            // TODO use chat notification.
            $this->factory->getConnection()->chatSendServerMessage("Processing queue. Maps in queue: ".count($this->addQueue));
            $this->addMap($data['login'], $map['mxid'], $map['mxsite']);
        }
    }

    /**
     * @param DedicatedMap $map
     * @param MxInfo       $mxInfo
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function persistMapData($map, $mxInfo)
    {

        $mapquery = new MapQuery();
        $dbMap = $mapquery->findOneByMapuid($map->uId);

        if ($dbMap) {
            $dbMap->fromArray($this->convertMap($map), TableMap::TYPE_FIELDNAME);
        } else {
            $dbMap = new Map();
            $dbMap->fromArray($this->convertMap($map), TableMap::TYPE_FIELDNAME);
        }

        $mxquery = new MxmapQuery();
        $mxMap = $mxquery->findOneByTrackuid($map->uId);

        if ($mxMap) {
            $mxMap->fromArray($mxInfo->toArray(), TableMap::TYPE_FIELDNAME);
        } else {
            $mxMap = new Mxmap();
            $mxMap->fromArray($mxInfo->toArray(), TableMap::TYPE_FIELDNAME);
        }
        $dbMap->addMxmap($mxMap);
        $dbMap->save();
        $mxMap->save();
    }

    /**
     * @param DedicatedMap $map
     * @return array
     */
    private function convertMap($map)
    {
        $outMap = (array) $map;
        $outMap["mapUid"] = $map->uId;

        return $outMap;

    }

    /**
     * Remove special characters from map name
     *
     * @param string $string
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
        // Nothin here.
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        // Nothin here.
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // Nothin here.
    }
}
