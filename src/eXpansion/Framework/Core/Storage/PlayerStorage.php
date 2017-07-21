<?php

namespace eXpansion\Framework\Core\Storage;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\Data\PlayerFactory;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\UnknownPlayerException;

/**
 * PlayerStorage keeps in storage player data in order to minimize amounts of calls done to the dedicated server.
 *
 * @package eXpansion\Framework\Core\Storage
 */
class PlayerStorage implements ListenerInterfaceMpLegacyPlayer, ListenerInterfaceExpTimer
{
    /** @var  Connection */
    protected $connection;

    /** @var PlayerFactory */
    protected $playerFactory;

    /** @var Player[] List of all the players on the server. */
    protected $online = [];

    /** @var Player[] List of all the players playing on the server. */
    protected $players = [];

    /** @var Player[] List of all spectators on the server. */
    protected $spectators = [];

    /** @var array */
    protected $playersToRemove = [];

    /**
     * PlayerDataProvider constructor.
     *
     * @param Connection $connection
     * @param PlayerFactory $playerFactory
     */
    public function __construct(Connection $connection, PlayerFactory $playerFactory)
    {
        $this->connection = $connection;
        $this->playerFactory = $playerFactory;
    }

    /**
     * Get information about a player.
     *
     * @param string $login
     * @param bool $forceNew
     *
     * @return Player
     */
    public function getPlayerInfo($login, $forceNew = false)
    {
        if (!isset($this->online[$login]) || $forceNew) {
            try {
                $playerInformation = $this->connection->getPlayerInfo($login);
                $playerDetails = $this->connection->getDetailedPlayerInfo($login);

                return $this->playerFactory->createPlayer($playerInformation, $playerDetails);
            } catch (UnknownPlayerException $e) {
                // @todo log unknown player error
                return new Player();
            }
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
        $this->playersToRemove[] = $playerData->getLogin();
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



    public function onPreLoop()
    {
        foreach ($this->playersToRemove as $login) {
            unset($this->online[$login]);
            unset($this->spectators[$login]);
            unset($this->players[$login]);
        }

        $this->playersToRemove = [];
    }

    public function onPostLoop()
    {
        // TODO: Implement onPostLoop() method.
    }

    public function onEverySecond()
    {
        // TODO: Implement onEverySecond() method.
    }

    /**
     * @return array
     */
    public function getPlayersToRemove()
    {
        return $this->playersToRemove;
    }
}
