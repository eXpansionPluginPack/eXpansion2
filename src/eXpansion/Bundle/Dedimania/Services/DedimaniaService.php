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
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Player;


class DedimaniaService
{

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
     * DedimaniaService constructor.
     * @param                     $titles
     * @param Connection          $connection
     * @param DedimaniaConnection $dedimaniaConnection
     * @param GameDataStorage     $gameDataStorage
     * @param Http                $http
     */
    public function __construct(
        $titles,
        Connection $connection,
        DedimaniaConnection $dedimaniaConnection,
        GameDataStorage $gameDataStorage,
        Http $http
    ) {

        $this->titles = $titles;
        $this->http = $http;
        $this->connection = $connection;
        $this->gameDataStorage = $gameDataStorage;
        $this->dedimaniaConnection = $dedimaniaConnection;
    }

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
            print_r($response);
        });

    }

    protected function getPackMask($titleId)
    {
        foreach ($this->titles as $title => $data) {
            if (in_array($titleId, $data)) {
                return ucfirst($title);
            }
        }
    }

}