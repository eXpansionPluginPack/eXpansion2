<?php

namespace eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * ChatDataProvider provides chat information to plugins.
 *
 * @package eXpansion\Framework\Core\DataProviders
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

    public function onBeginMap($map)
    {
        $this->dispatch(__FUNCTION__, [Map::fromArray($map)]);
    }

    public function onEndMap($map)
    {

        $this->dispatch(__FUNCTION__, [Map::fromArray($map)]);
    }
}
