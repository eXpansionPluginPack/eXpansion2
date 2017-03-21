<?php

namespace eXpansion\Core\DataProviders;

use eXpansion\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * ChatDataProvider provides chat information to plugins.
 *
 * @package eXpansion\Core\DataProviders
 */
class MatchDataProvider extends AbstractDataProvider
{
    /** @var  PlayerStorage */
    protected $playerStorage;


    /** @var  Connection */
    protected $connection;

    /**
     * MatchDataProvider constructor.
     *
     * @param PlayerStorage $playerStorage
     * @param Connection $connection
     */
    public function __construct(PlayerStorage $playerStorage, Connection $connection)
    {
        $this->playerStorage = $playerStorage;
        $this->connection = $connection;
    }

    public function onBeginMatch()
    {
        $this->dispatch(__FUNCTION__, []);
    }

    public function onEndMatch()
    {
        $this->dispatch(__FUNCTION__, []);
    }

    public function onBeginMap($map)
    {
        $this->dispatch(__FUNCTION__, [Map::fromArray($map)]);
    }

    public function onEndMap($map)
    {

        $this->dispatch(__FUNCTION__, [Map::fromArray($map)]);
    }

    public function onBeginRound()
    {
        $this->dispatch(__FUNCTION__, []);
    }

    public function onEndRound()
    {
        $this->dispatch(__FUNCTION__, []);
    }

    /**
     * @param $uid
     * @param $login
     * @param $data
     */
    public function onPlayerFinish($uid, $login, $data)
    {
        $this->dispatch(__FUNCTION__, [$this->playerStorage->getPlayerInfo($login), (float) $data]);
    }

    /**
     * @param $uid
     * @param $login
     * @param $time
     * @param $currentLap
     * @param $index
     */
    public function onPlayerCheckpoint($uid, $login, $time, $currentLap, $index)
    {
        $this->dispatch(__FUNCTION__, [$this->playerStorage->getPlayerInfo($login), (float) $time, (int) $currentLap, (int) $index]);
    }


}
