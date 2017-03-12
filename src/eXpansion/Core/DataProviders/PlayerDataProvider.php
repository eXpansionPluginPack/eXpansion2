<?php

namespace eXpansion\Core\DataProviders;

use eXpansion\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class PlayerDataProvider provides information to plugins about what is going on with players.
 *
 * @TODO handle disconnect
 * @TODO handle info changes.
 * @TODO handle allies changes.
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
            $this->dispatch(__FUNCTION__, [$playerData]);
        } catch (\Exception $e) {
            // TODO log that player disconnected very fast.
        }
    }
}