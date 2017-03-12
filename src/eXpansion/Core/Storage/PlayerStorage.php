<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 16:12
 */

namespace eXpansion\Core\Storage;

use eXpansion\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;

/**
 * PlayerStorage keeps in storage player data in order to minimize amounts of calls done to the dedicated server.
 *
 * @TODO handle different situations.
 *
 * @package eXpansion\Core\Storage
 */
class PlayerStorage implements PlayerDataListenerInterface
{
    /** @var  Connection */
    protected $connection;

    /** @var Player[] List of all the players on the server. */
    protected $onlinePlayers = [];

    /** @var Player[] List of all the players playing on the server. */
    protected $players = [];

    /** @var Player[] List of all spectators on the server. */
    protected $spectators = [];

    /**
     * PlayerDataProvider constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Get information about a player.
     *
     * @param $login
     *
     * @return Player
     */
    public function getPlayerInfo($login)
    {
        if (!isset($this->onlinePlayers[$login])) {
            $playerInformation = $this->connection->getPlayerInfo($login);
            $playerDetails = $this->connection->getDetailedPlayerInfo($login);

            $playerData = new Player();
            $playerData->merge($playerInformation)
                ->merge($playerDetails);

            return $playerData;
        }

        return $this->onlinePlayers[$login];
    }

    /**
     * @inheritdoc
     *
     * Fetch player data & store it when player connects.
     */
    public function onPlayerConnect(Player $playerData)
    {
        $login = $playerData->getLogin();

        $this->onlinePlayers[$login] = $playerData;

        if ($playerData->isSpectator()) {
            $this->spectators[$login] = $playerData;
        } else {
            $this->players[$login] = $playerData;
        }
    }
}