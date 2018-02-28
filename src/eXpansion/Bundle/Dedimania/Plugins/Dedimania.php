<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 10.50
 */

namespace eXpansion\Bundle\Dedimania\Plugins;


use eXpansion\Bundle\Dedimania\Classes\IXR_Base64;
use eXpansion\Bundle\Dedimania\Classes\Request;
use eXpansion\Bundle\Dedimania\Services\DedimaniaService;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\FileSystem;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player as DedicatedPlayer;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use eXpansion\Framework\GameManiaplanet\ScriptMethods\GetScores;
use eXpansion\Framework\GameTrackmania\DataProviders\Listener\ListenerInterfaceRaceData;
use eXpansion\Framework\Notifications\Services\Notifications;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;
use Maniaplanet\DedicatedServer\Structures\Player;
use Maniaplanet\DedicatedServer\Xmlrpc\Request as XmlRpcRequest;


class Dedimania implements StatusAwarePluginInterface, ListenerInterfaceExpTimer, ListenerInterfaceMpScriptMap, ListenerInterfaceMpScriptMatch, ListenerInterfaceRaceData, ListenerInterfaceMpLegacyPlayer
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
     * @var GetScores
     */
    private $getScores;
    /**
     * @var FileSystem
     */
    private $fileSystem;


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
     * @param GetScores        $getScores
     * @param FileSystem       $fileSystem
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
        Time $time,
        GetScores $getScores,
        FileSystem $fileSystem
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
        $this->getScores = $getScores;
        $this->fileSystem = $fileSystem;
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
            }
        } catch (\Exception $e) {
            $this->console->writeln("Dedimania: periodic keep-alive failed: ".$e->getMessage());
        }
    }


    /**
     * Send a request to Dedimania
     *
     * @param Request  $request
     * @param callable $callback
     */
    final public function sendRequest(Request $request, $callback)
    {
        $this->webaccess->request(
            self::dedimaniaUrl,
            [[$this, "process"], $callback],
            $request->getXml(),
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

                $message = [];
                try {
                    if (isset($response['Message'])) {
                        $message = XmlRpcRequest::decode($response['Message']);
                    } else {
                        $this->console->writeln("Dedimania: Error received non-xmlrpc string. See dump below:");
                        $this->console->writeln(Console::error.$response);
                    }
                } catch (\Exception $ex) {
                    $this->console->writeln("Dedimania: Error received non-xmlrpc string. See dump below:");
                    $this->console->writeln(Console::error.$response['Message']);

                    return;
                }
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

                if (sizeof($array) == 1) {
                    call_user_func_array($callback, [$array[0][0]]);
                } elseif (sizeof($array) > 1) {
                    $out = [];
                    foreach ($array as $key => $value) {
                        $out[] = $value[0];
                    }
                    call_user_func_array($callback, [$out]);
                }

                return;
            } else {
                $this->console->writeln('Dedimania Error: $f00Can\'t find Message from Dedimania reply');
            }
        } catch (\Exception $e) {
            $this->console->writeln('Dedimania Error: $f00Connection to dedimania server failed.'.$e->getMessage()." trace:".$e->getTraceAsString());
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

        $this->sendRequest($request, function ($response) {
            $this->sessionId = $response['SessionId'];
            $this->getRecords();
            $this->connectAllPlayers();
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
        $that = $this;

        $this->sendRequest($request, function ($response) {

            $this->dedimaniaService->setServerMaxRank($response['ServerMaxRank']);
            /** @var DedimaniaRecord[] $recs */
            $recs = DedimaniaRecord::fromArrayOfArray($response['Records']);
            if (isset($response['Records']['Login'])) {
                $recs = [0 => $recs];
            }
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

        $this->sendRequest($request, function ($response) {
            // do nothing
        });

    }

    /**
     * @param DedicatedPlayer $player
     */
    public function connectPlayer(DedicatedPlayer $player)
    {
        $params = [
            $this->sessionId,
            $player->getLogin(),
            $player->getNickName(),
            $player->getPath(),
            $player->isSpectator(),
        ];

        $request = new Request('dedimania.PlayerConnect', $params);
        $this->sendRequest($request, function ($response) {
            $this->dedimaniaService->connectPlayer(DedimaniaPlayer::fromArray($response));
        });
    }

    /**
     * @param DedicatedPlayer $player
     */
    public function disconnectPlayer(DedicatedPlayer $player)
    {
        $params = [
            $this->sessionId,
            $player->getLogin(),
            $player->getNickName(),
            $player->getPath(),
            $player->isSpectator(),
        ];

        $request = new Request('dedimania.PlayerConnect', $params);
        $this->sendRequest($request, function ($response) use ($player) {
            $this->dedimaniaService->disconnectPlayer($player->getLogin());
        });
    }

    public function connectAllPlayers()
    {
        $players = $this->playerStorage->getOnline();

        /** @var Request $request */
        $request = null;
        foreach ($players as $x => $player) {
            $params = [
                $this->sessionId,
                $player->getLogin(),
                $player->getNickName(),
                $player->getPath(),
                $player->isSpectator(),
            ];
            if ($request === null) {
                $request = new Request('dedimania.PlayerConnect', $params);
            } else {
                $request->add('dedimania.PlayerConnect', $params);
            }
        }
        
        // no players to connect
        if ($request === null) {
            return;
        }

        $this->sendRequest($request, function ($response) {

            if (array_key_exists("Login", $response)) {
                $response = [0 => $response];
            }
            $this->dedimaniaService->setPlayers(DedimaniaPlayer::fromArrayOfArray($response));
        });
    }

    /*
     */

    public function setRecords()
    {
        $that = $this;
        $this->getScores->get(function ($scores) use ($that) {
            if (count($scores['players']) > 0 && isset($scores['players'][0]['login'])) {

                $player = new Player();
                $player->login = $scores['players'][0]['login'];
                try {
                    $replay = new IXR_Base64($this->connection->getValidationReplay($player));
                    $that->dedimaniaService->setVReplay($replay);
                    $VReplayChecks = $scores['players'][0]['bestracecheckpoints'];

                    $times = [];
                    foreach ($scores['players'] as $player) {
                        $times[] = [
                            "Login" => $player['login'],
                            "Best" => $player['bestracetime'],
                            "Checks" => implode(",", $player['bestracecheckpoints']),
                        ];
                    }

                    $params = [
                        $this->sessionId,
                        $this->getMapInfo(),
                        $this->getGameMode(),
                        $times,
                        $this->getReplays($VReplayChecks),
                    ];

                    $request = new Request("dedimania.SetChallengeTimes", $params);

                    $that->sendRequest($request, function ($response) {
                        $this->console->writeln('Dedimania: $0f0records saved.');
                    });
                } catch (\Exception $e) {
                    $this->console->writeln('Dedimania: Can\'t send times, $f00'.$e->getMessage());
                }
            }

        });

    }

//endregion
//#region protected helper functions

    protected function getReplays($bestCheckpoints)
    {
        return [
            "VReplay" => $this->dedimaniaService->getVReplay(),
            "VReplayChecks" => implode(",", $bestCheckpoints),
            "Top1GReplay" => $this->dedimaniaService->getGReplay(),
        ];

    }


    protected function setGReplay($login)
    {
        $player = new Player();
        $player->login = $login;
        try {
            $this->connection->saveBestGhostsReplay($player, "exp2_temp_replay");
            $replay = new IXR_Base64(
                $this->fileSystem->getUserData()->readAndDelete(
                    "Replays".DIRECTORY_SEPARATOR."exp2_temp_replay.Replay.Gbx")
            );
            $this->dedimaniaService->setGReplay($replay);
        } catch (\Exception $e) {
            $this->console->writeln('Dedimania: $f00Error while fetching GhostsReplay');
        }


    }

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
        $map = $this->connection->getCurrentMapInfo();

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
//#region Dedicated server callbacks

    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        if (!$restarted) {
            $this->lastUpdate = time();
            $this->getRecords();
        }
    }

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     */
    public function onEndMapStart($count, $time, $restarted, Map $map)
    {
        if (!$restarted) {

            $this->setRecords();

        }
    }

    /**
     * @param string $login Login of the player that crossed the CP point
     * @param int    $time Server time when the event occured,
     * @param int    $raceTime Total race time in milliseconds
     * @param int    $stuntsScore Stunts score
     * @param int    $cpInRace Number of checkpoints crossed since the beginning of the race
     * @param int[]  $curCps Checkpoints times since the beginning of the race
     * @param string $blockId Id of the checkpoint block
     * @param string $speed Speed of the player in km/h
     * @param string $distance Distance traveled by the player
     */
    public function onPlayerEndRace(
        $login,
        $time,
        $raceTime,
        $stuntsScore,
        $cpInRace,
        $curCps,
        $blockId,
        $speed,
        $distance
    ) {
        $rank = $this->dedimaniaService->processRecord($login, $raceTime, $curCps);
        if ($rank > 0) {
            if ($rank === 1) {
                $this->setGReplay($login);
            }
            $this->console->write("new dedimania record".$rank);
        } else {
            $this->console->writeln("no new record");

        }
    }

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {

    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchStart($count, $time)
    {
        // do nothing
    }


    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int     $count Each time this section is played, this number is incremented by one
     * @param int     $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map Map started with.
     *
     * @return void
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map)
    {
        // do nothing
    }

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchStart($count, $time)
    {
        // do nothing
    }

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchEnd($count, $time)
    {
        // do nothing
    }


    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchEnd($count, $time)
    {
        // do nothing
    }

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundEnd($count, $time)
    {
        //
    }


    /**
     * @param DedicatedPlayer $player
     * @return void
     */
    public function onPlayerConnect(DedicatedPlayer $player)
    {
        $this->connectPlayer($player);
    }

    /**
     * @param DedicatedPlayer $player
     * @param string          $disconnectionReason
     * @return void
     */
    public function onPlayerDisconnect(DedicatedPlayer $player, $disconnectionReason)
    {
        $this->disconnectPlayer($player);
    }

    /**
     * @param DedicatedPlayer $oldPlayer
     * @param DedicatedPlayer $player
     * @return void
     */
    public function onPlayerInfoChanged(
        DedicatedPlayer $oldPlayer,
        DedicatedPlayer $player
    ) {
        //
    }

    /**
     * @param DedicatedPlayer $oldPlayer
     * @param DedicatedPlayer $player
     * @return void
     */
    public function onPlayerAlliesChanged(
        DedicatedPlayer $oldPlayer,
        DedicatedPlayer $player
    ) {
        //
    }
}