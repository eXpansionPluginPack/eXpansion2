<?php

namespace eXpansion\Core\DataProviders;

use eXpansion\Core\Storage\MapStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\IndexOutOfBoundException;

/**
 * Class MapDataProvider provides information to plugins about what is going on with the maps on the server.
 *
 * @package eXpansion\Core\DataProviders
 */
class MapDataProvider extends AbstractDataProvider
{
    /** Size of batch to get maps from the storage. */
    const BATCH_SIZE = 500;

    /**
     * @var MapStorage
     */
    protected $mapStorage;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * PlayerDataProvider constructor.
     *
     * @param MapStorage $mapStorage
     * @param Connection $connection
     */
    public function __construct(MapStorage $mapStorage, Connection $connection)
    {
        $this->mapStorage = $mapStorage;
        $this->connection = $connection;
    }

    /**
     * Called when eXpansion is started.
     */
    public function onRun()
    {
        $this->updateMapList();

    }

    /**
     * Update the list of maps in the storage.
     */
    protected function updateMapList()
    {
        $start = 0;

        do {
            try {
                $maps = $this->connection->getMapList(self::BATCH_SIZE, $start);
            } catch (IndexOutOfBoundException $e) {
                // This is normal error when we we are trying to find all maps and we are out of bounds.
                return;
            }

            foreach ($maps as $map) {
                $this->mapStorage->addMap($map);
            }

            $start += self::BATCH_SIZE;

        } while(count($maps) == self::BATCH_SIZE);
    }

    /**
     * Called when map list is modified.
     *
     * @param $curMapIndex
     * @param $nextMapIndex
     * @param $isListModified
     *
     */
    function onMapListModified($curMapIndex, $nextMapIndex, $isListModified)
    {
        if ($isListModified) {
            $oldMaps = $this->mapStorage->getMaps();

            $this->mapStorage->resetMapData();
            $this->updateMapList();

            // We will dispatch even only when list is modified. If not we dispatch specific events.
            $this->dispatch(__FUNCTION__, [$oldMaps, $curMapIndex, $nextMapIndex]);
        }

        $currentMap = $this->mapStorage->getMap($curMapIndex);
        if ($this->mapStorage->getCurrentMap()->uId != $curMapIndex) {
            $previousMap = $this->mapStorage->getCurrentMap();
            $this->mapStorage->setCurrentMap($currentMap);

            $this->dispatch('onExpansionMapChange', [$currentMap, $previousMap]);
        }

        $nextMap = $this->mapStorage->getMap($nextMapIndex);
        if ($this->mapStorage->getNextMap()->uId != $nextMapIndex) {
            $previousNextMap = $this->mapStorage->getNextMap();
            $this->mapStorage->setNextMap($nextMap);

            $this->dispatch('onExpansionNextMapChange', [$nextMap, $previousNextMap]);
        }
    }
}
