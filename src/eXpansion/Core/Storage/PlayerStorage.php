<?php

namespace eXpansion\Core\Storage;

use eXpansion\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Core\Storage\Data\Player;
use eXpansion\Core\Storage\Data\PlayerFactory;
use Maniaplanet\DedicatedServer\Connection;

/**
 * PlayerStorage keeps in storage player data in order to minimize amounts of calls done to the dedicated server.
 *
 * @package eXpansion\Core\Storage
 */
class PlayerStorage implements PlayerDataListenerInterface
{
    /** @var  Connection */
    protected $connection;

    /** @var PlayerFactory  */
    protected $playerFactory;

    /** @var Player[] List of all the players on the server. */
    protected $online = [];

    /** @var Player[] List of all the players playing on the server. */
    protected $players = [];

    /** @var Player[] List of all spectators on the server. */
    protected $spectators = [];

    /**
     * PlayerDataProvider constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection, PlayerFactory $playerFactory)
    {
        $this->connection = $connection;
        $this->playerFactory = $playerFactory;
    }

    /**
     * Get information about a player.
     *
     * @param $login
     *
     * @return Player
     */
    public function getPlayerInfo($login, $forceNew = false)
    {
        if (!isset($this->online[$login]) || $forceNew) {
            $playerInformation = $this->connection->getPlayerInfo($login);
            $playerDetails = $this->connection->getDetailedPlayerInfo($login);

            return $this->playerFactory->createPlayer($playerInformation, $playerDetails);
        }

        return $this->online[$login];
    }

    /**
     * Fetch player data & store it when player connects.
     *
     * @inheritdoc
     */
    public function onPlayerConnect(Player $playerData)
    {
        $login = $playerData->getLogin();

        $this->online[$login] = $playerData;

        if ($playerData->isSpectator()) {
            $this->spectators[$login] = $playerData;
        } else {
            $this->players[$login] = $playerData;
        }
    }

    /**
     * Remove player data when he disconnects.
     *
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $playerData, $disconnectionReason)
    {
        unset($this->online[$playerData->getLogin()]);
        unset($this->spectators[$playerData->getLogin()]);
        unset($this->players[$playerData->getLogin()]);
    }

    /**
     * Change the status of the players.
     *
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        unset($this->players[$player->getLogin()]);
        unset($this->spectators[$player->getLogin()]);

        $this->onPlayerConnect($player);
    }

    /**
     * Modify the player object.
     *
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        $this->onPlayerConnect($player);
    }

    /**
     * @return Player[]
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * @return Player[]
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @return Player[]
     */
    public function getSpectators()
    {
        return $this->spectators;
    }
}