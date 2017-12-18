<?php

namespace eXpansion\Framework\Core\Storage;

use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class MapStorage stores data on the maps to be played & that is being currently played.
 *
 * @package eXpansion\Framework\Core\Storage
 * @author Oliver de Cramer
 */
class MapStorage
{
    /** @var Connection */
    protected $connection;

    /** @var Map[] List of all current maps on the server. */
    protected $maps = [];

    /** @var Map Current map being played. */
    protected $currentMap;

    /** @var Map Next map to be played. */
    protected $nextMap;

    /**
     * MapStorage constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Add a map to the current map list.
     *
     * @param Map $map
     *
     */
    public function addMap(Map $map)
    {
        $this->maps[$map->uId] = $map;
    }

    /**
     * Get current map list.
     *
     * @return Map[]
     */
    public function getMaps()
    {
        return $this->maps;
    }

    /**
     * Get a map.
     *
     * @param string $uid The unique id of the map to get.
     *
     * @return Map
     */
    public function getMap($uid)
    {
        /** @var Map $map */
        $map = AssociativeArray::getFromKey($this->maps, $uid,  new Map());

        if ($map->fileName && $map->lapRace === null) {
            $map = $this->connection->getMapInfo($map->fileName);
            $this->maps[$map->uId] = $map;
        }

        return $map;
    }

    /**
     * Get a map.
     *
     * @param integer $index the index number of the map to fetch
     *
     * @return Map|null
     */
    public function getMapByIndex($index)
    {
        $map = array_slice($this->maps, (int)$index, 1, false);

        return end($map);
    }


    /**
     * Reset map data.
     */
    public function resetMapData()
    {
        $this->maps = [];
    }

    /**
     * Get current map being played.
     *
     * @return Map
     */
    public function getCurrentMap()
    {
        return $this->currentMap;
    }

    /**
     * Set the current map when it's changed.
     *
     * @param Map $currentMap
     */
    public function setCurrentMap(Map $currentMap)
    {
        $this->currentMap = $currentMap;
    }

    /**
     * Get next map to be played.
     *
     * @return Map
     */
    public function getNextMap()
    {
        return $this->nextMap;
    }

    /**
     * Set the next map that is going to be played.
     *
     * @param Map $nextMap
     */
    public function setNextMap($nextMap)
    {
        $this->nextMap = $nextMap;
    }
}
