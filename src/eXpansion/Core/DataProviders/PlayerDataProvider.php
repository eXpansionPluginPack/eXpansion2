<?php

namespace eXpansion\Core\DataProviders;

use eXpansion\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;

/**
 * Class PlayerDataProvider provides information to plugins about what is going on with players.
 *
 * @package eXpansion\Core\DataProviders
 */
class PlayerDataProvider extends AbstractDataProvider
{
    /**
     * @var PlayerStorage
     */
    protected $playerStorage;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * PlayerDataProvider constructor.
     * @param $playerStorage
     */
    public function __construct(PlayerStorage $playerStorage, Connection $connection)
    {
        $this->playerStorage = $playerStorage;
        $this->connection = $connection;
    }

    /**
     * Called when eXpansion is started.
     */
    public function onRun()
    {
        $infos = $this->connection->getPlayerList(-1, 0);
        foreach ($infos as $info) {
            $this->onPlayerConnect($info->login);
        }
    }

    /**
     * Called when a player connects
     *
     * @param string $login
     * @param bool $isSpectator
     */
    public function onPlayerConnect($login, $isSpectator = false)
    {
        try {
            $playerData = $this->playerStorage->getPlayerInfo($login);
        } catch (\Exception $e) {
            // TODO log that player disconnected very fast.
            return;
        }

        $this->playerStorage->onPlayerConnect($playerData);
        $this->dispatch(__FUNCTION__, [$playerData]);
    }

    /**
     * Called when a player disconnects
     *
     * @param $login
     * @param $disconnectionReason
     */
    public function onPlayerDisconnect($login, $disconnectionReason)
    {
        $playerData = $this->playerStorage->getPlayerInfo($login);
        $this->playerStorage->onPlayerDisconnect($playerData, $disconnectionReason);

        $this->dispatch(__FUNCTION__, [$playerData, $disconnectionReason]);
    }

    /**
     * When user information changes (changes from spec to player...)
     *
     * @param PlayerInfo $playerInfo
     */
    public function onPlayerInfoChanged($playerInfo)
    {
        $playerData = $this->playerStorage->getPlayerInfo($playerInfo['Login']);

        $newPlayerData = clone $playerData;
        $newPlayerData->merge($playerInfo);

        $this->playerStorage->onPlayerInfoChanged($playerData, $newPlayerData);
        $this->dispatch(__FUNCTION__, [$playerData, $newPlayerData]);
    }

    /**
     * When player changes allies.
     *
     */
    public function onPlayerAlliesChanged($login)
    {
        $newPlayerData = $this->playerStorage->getPlayerInfo($login, true);
        $playerData = $this->playerStorage->getPlayerInfo($login);

        $this->playerStorage->onPlayerAlliesChanged($playerData, $newPlayerData);
        $this->dispatch(__FUNCTION__, [$playerData, $newPlayerData]);
    }
}