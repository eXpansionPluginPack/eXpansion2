<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 10.50
 */

namespace eXpansion\Bundle\Dedimania\Plugins;


use eXpansion\Bundle\Dedimania\Classes\Request;
use eXpansion\Bundle\Dedimania\Services\DedimaniaService;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Notifications\Services\Notifications;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;
use Maniaplanet\DedicatedServer\Structures\Player;
use Maniaplanet\DedicatedServer\Xmlrpc\Request as XmlRpcRequest;


class Dedimania implements StatusAwarePluginInterface, ListenerInterfaceExpTimer, ListenerInterfaceMpLegacyMap
{
    const dedimaniaUrl = "http://dedimania.net:8081/Dedimania";

    /**
     * @var DedimaniaService
     */
    private $dedimaniaService;
    /**
     * @var ConfigInterface
     */
    private $enabled;
    /**
     * @var ConfigInterface
     */
    private $apikey;
    /**
     * @var ConfigInterface
     */
    private $serverLogin;

    private $read = [];
    private $write = [];
    private $except = [];

    /** @var \Webaccess */
    protected $webaccess;
    /**
     * @var Console
     */
    private $console;
    /** @var int */
    private $tryTimer = -1;

    /** @var int */
    private $lastUpdate;
    /** @var string|null */
    private $sessionId = null;
    private $titles;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var Notifications
     */
    private $notifications;
    /**
     * @var Time
     */
    private $time;


    /**
     * Dedimania constructor.
     * @param                  $titles
     * @param ConfigInterface  $enabled
     * @param ConfigInterface  $apikey
     * @param ConfigInterface  $serverLogin
     * @param DedimaniaService $dedimaniaService
     * @param Console          $console
     * @param Connection       $connection
     * @param GameDataStorage  $gameDataStorage
     * @param MapStorage       $mapStorage
     * @param PlayerStorage    $playerStorage
     * @param Notifications    $notifications
     * @param Time             $time
     */
    public function __construct(
        $titles,
        ConfigInterface $enabled,
        ConfigInterface $apikey,
        ConfigInterface $serverLogin,
        DedimaniaService $dedimaniaService,
        Console $console,
        Connection $connection,
        GameDataStorage $gameDataStorage,
        MapStorage $mapStorage,
        PlayerStorage $playerStorage,
        Notifications $notifications,
        Time $time
    ) {
        require_once(dirname(__DIR__).DIRECTORY_SEPARATOR."Classes".DIRECTORY_SEPARATOR."Webaccess.php");
        $this->webaccess = new \Webaccess($console);
        $this->console = $console;
        $this->dedimaniaService = $dedimaniaService;
        $this->enabled = $enabled;
        $this->apikey = $apikey;
        $this->serverLogin = $serverLogin;
        $this->titles = $titles;
        $this->connection = $connection;
        $this->gameDataStorage = $gameDataStorage;
        $this->mapStorage = $mapStorage;
        $this->playerStorage = $playerStorage;
        $this->notifications = $notifications;
        $this->time = $time;
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     * @throws \Exception
     */
    public function setStatus($status)
    {
        if ($status && $this->enabled->get()) {
            try {
                $this->lastUpdate = time();
                $this->openSession($this->serverLogin->get(), $this->apikey->get());
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
    }


    /** @api */
    public function onPreLoop()
    {
        // do nothing
    }

    /** @api */
    public function onPostLoop()
    {
        try {
            if ($this->tryTimer == -1) {
                $this->webaccess->select($this->read, $this->write, $this->except, 0, 0);
            } else {
                if (time() >= $this->tryTimer) {
                    $this->console->writeln("Webaccess: Main Loop active again!");
                    $this->tryTimer = -1;
                }
            }
        } catch (\Exception $e) {
            $this->console->writeln(
                'Webaccess: OnTick Update $f00failed$555, trying to retry in 2 seconds...');
            $this->tryTimer = time() + 2;
        }
    }

    /** @api */
    public function onEverySecond()
    {
        try {
            if ($this->sessionId !== null && (time() - $this->lastUpdate) > 3 * 60) {
                $this->lastUpdate = time();
                $this->console->writeln("Dedimania: sent connection keep-alive!");
                $this->updateServerPlayers();
                $this->console->writeln("$0f0Dedimania: Should now update players for current map");
            }
        } catch (\Exception $e) {
            $this->console->writeln("Dedimania: periodic keep-alive failed: ".$e->getMessage());
        }
    }


    /**
     * Send a request to Dedimania
     *
     * @param string   $request
     * @param callable $callback
     */
    final public function sendRequest($request, $callback)
    {
        $this->webaccess->request(
            self::dedimaniaUrl,
            [[$this, "process"], $callback],
            $request,
            true,
            600,
            3,
            5,
            'eXpansion server controller',
            'application/x-www-form-urlencoded; charset=UTF-8'
        );
    }

    final public function process($response, $callback)
    {

        try {

            if (is_array($response) && array_key_exists('Message', $response)) {

                $message = XmlRpcRequest::decode($response['Message']);
                $errors = end($message[1]);

                if (count($errors) > 0 && array_key_exists('methods', $errors[0])) {
                    foreach ($errors[0]['methods'] as $error) {
                        if (!empty($error['errors'])) {
                            $this->console->writeln('Dedimania error on method: $fff'.$error['methodName']);
                            $this->console->writeln('$f00'.$error['errors']);
                        }
                    }
                }

                $array = $message[1];
                unset($array[count($array) - 1]); // remove trailing errors and info

                if (array_key_exists("faultString", $array[0])) {
                    $this->console->writeln('Dedimania fault:$f00 '.$array[0]['faultString']);

                    return;
                }

                if (!empty($array[0][0]['Error'])) {
                    $this->console->writeln('Dedimania error:$f00 '.$array[0][0]['Error']);

                    return;
                }

                call_user_func_array($callback, [$array[0][0]]);

                return;
            } else {
                $this->console->writeln('Dedimania Error: $f00Can\'t find Message from Dedimania reply');
            }
        } catch (\Exception $e) {
            $this->console->writeln('Dedimania Error: $f00Connection to dedimania server failed.'.$e->getMessage());
        }
    }

    //#region public dedimania methods

    /**
     * @param string $serverlogin
     * @param string $apikey
     * @throws \Exception
     *
     */
    public function openSession($serverlogin, $apikey)
    {
        $this->sessionId = null;
        $server = new Player();
        $server->login = $this->gameDataStorage->getSystemInfo()->serverLogin;

        $info = $this->connection->getDetailedPlayerInfo($server);

        $packMask = $this->getPackMask($this->gameDataStorage->getVersion()->titleId);
        if (!$packMask) {
            throw new \Exception("Packmask not found for titleId");
        }
        $params = [
            "Game" => "TM2",
            "Login" => $serverlogin,
            "Code" => $apikey,
            "Path" => $info->path,
            "Packmask" => $packMask,
            "ServerVersion" => $this->gameDataStorage->getVersion()->version,
            "ServerBuild" => $this->gameDataStorage->getVersion()->build,
            "Tool" => "eXpansion",
            "Version" => AbstractApplication::EXPANSION_VERSION,
            "ServerIp" => $this->gameDataStorage->getSystemInfo()->publishedIp,
        ];

        $request = new Request('dedimania.OpenSession', [$params]);

        $this->sendRequest($request->getXml(), function ($response) {
            $this->sessionId = $response['SessionId'];
            $this->getRecords();
        });
    }

    public function getRecords()
    {
        if ($this->sessionId == null) {
            return;
        }

        $params = [
            $this->sessionId,
            $this->getMapInfo(),
            $this->getGameMode(),
            $this->getServerInfo(),
            $this->getPlayers(),
        ];

        $request = new Request('dedimania.GetChallengeRecords', $params);

        $this->sendRequest($request->getXml(), function ($response) {
            $this->dedimaniaService->setServerMaxRank($response['ServerMaxRank']);
            /** @var DedimaniaRecord[] $recs */
            $recs = DedimaniaRecord::fromArrayOfArray($response['Records']);
            $this->dedimaniaService->setDedimaniaRecords($recs);

            if (!empty($recs) && count($recs) > 0) {
                $time = $this->time->timeToText($recs[0]->best, true);
                $this->notifications->info(
                    "Found ".count($recs)." records!\n#1 ".$recs[0]->nickName.'$z('.$recs[0]->login.'), time:'.$time,
                    [], "Dedimania", 10500);
            } else {
                $this->notifications->info(
                    "Found 0 records",
                    [], "Dedimania", 10500);
            }

        });

    }

    public function updateServerPlayers()
    {
        if ($this->sessionId == null) {
            return;
        }

        $params = [
            $this->sessionId,
            $this->getServerInfo(),
            $this->getVotesInfo(),
            $this->getPlayers(),
        ];
        $request = new Request('dedimania.UpdateServerPlayers', $params);

        $this->sendRequest($request->getXml(), function ($response) {
            // do nothing
        });

    }


//endregion
//#region protected helper functions

    protected function getVotesInfo()
    {
        $map = $this->mapStorage->getCurrentMap();

        return [
            "UId" => $map->uId,
            "GameMode" => $this->getGameMode(),
        ];
    }

    protected function getMapInfo()
    {
        $map = $this->mapStorage->getCurrentMap();

        return [
            "UId" => $map->uId,
            "Name" => $map->name,
            "Environment" => $map->environnement,
            "Author" => $map->author,
            "NbCheckpoints" => $map->nbCheckpoints,
            "NbLaps" => $map->nbLaps,
        ];
    }


    protected function getPlayers()
    {
        $players = [];
        foreach ($this->playerStorage->getPlayers() as $player) {
            $players [] = [
                "Login" => $player->getLogin(),
                "IsSpec" => $player->isSpectator(),
            ];
        }

        return $players;
    }

    protected function getServerInfo()
    {
        return [
            "SrvName" => $this->gameDataStorage->getServerOptions()->name,
            "Comment" => $this->gameDataStorage->getServerOptions()->comment ?: "",
            "Private" => $this->gameDataStorage->getServerOptions()->password ? true : false,
            "NumPlayers" => count($this->playerStorage->getPlayers()),
            "MaxPlayers" => intval($this->gameDataStorage->getServerOptions()->currentMaxPlayers),
            "NumSpecs" => count($this->playerStorage->getSpectators()),
            "MaxSpecs" => intval($this->gameDataStorage->getServerOptions()->currentMaxSpectators),
        ];
    }

    protected function getGameMode()
    {
        switch (strtolower($this->gameDataStorage->getGameInfos()->scriptName)) {

            case "timeattack.script.txt":
            case "laps.script.txt":
                return "TA";
            case "rounds.script.txt":
            case "team.script.txt":
            case "cup.script.txt":
                return "Rounds";
            default:
                return "";
        }
    }

    protected function getPackMask($titleId)
    {
        foreach ($this->titles as $title => $data) {
            if (in_array($titleId, $data)) {
                return ucfirst($title);
            }
        }

        return "";
    }

//#endregion

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map)
    {
        $this->lastUpdate = time();
        $this->getRecords();
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onEndMap(Map $map)
    {

    }
}