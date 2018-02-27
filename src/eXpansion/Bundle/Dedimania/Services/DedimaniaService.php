<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 27.2.2018
 * Time: 10.06
 */

namespace eXpansion\Bundle\Dedimania\Services;


use eXpansion\Bundle\Dedimania\Classes\Request;
use eXpansion\Bundle\Dedimania\Plugins\DedimaniaConnection;
use eXpansion\Framework\Core\Helpers\Http;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Player;


class DedimaniaService
{
    //#region variables and constructor
    /** @var array */
    private $titles;

    /**
     * @var Http
     */
    private $http;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;

    /** @var string */
    private $sessionId = null;
    /**
     * @var DedimaniaConnection
     */
    private $dedimaniaConnection;

    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var MapStorage
     */
    private $mapStorage;


    /**
     * DedimaniaService constructor.
     * @param                     $titles
     * @param Connection          $connection
     * @param DedimaniaConnection $dedimaniaConnection
     * @param GameDataStorage     $gameDataStorage
     * @param MapStorage          $mapStorage
     * @param PlayerStorage       $playerStorage
     */
    public function __construct(
        $titles,
        Connection $connection,
        DedimaniaConnection $dedimaniaConnection,
        GameDataStorage $gameDataStorage,
        MapStorage $mapStorage,
        PlayerStorage $playerStorage
    ) {

        $this->titles = $titles;
        $this->connection = $connection;
        $this->dedimaniaConnection = $dedimaniaConnection;
        $this->gameDataStorage = $gameDataStorage;
        $this->mapStorage = $mapStorage;
        $this->playerStorage = $playerStorage;
    }

    //#endregion
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

        $this->dedimaniaConnection->sendRequest($request->getXml(), function ($response) {
            $this->sessionId = $response['SessionId'];

            print_r($response);
            var_dump($this->sessionId);

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

        print_r($this->getServerInfo());


        $request = new Request('dedimania.GetChallengeRecords', $params);

        $this->dedimaniaConnection->sendRequest($request->getXml(), function ($response) {
            print_r($response);
        });


    }

//endregion
//#region protected helper functions

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
    }

//#endregion
}