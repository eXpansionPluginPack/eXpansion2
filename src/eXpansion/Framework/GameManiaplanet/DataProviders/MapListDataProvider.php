<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Storage\MapStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Xmlrpc\IndexOutOfBoundException;
use Maniaplanet\DedicatedServer\Xmlrpc\NextMapException;

/**
 * Class MapDataProvider provides information to plugins about what is going on with the maps on the server.
 *
 * @package eXpansion\Framework\Core\DataProviders
 */
class MapListDataProvider extends AbstractDataProvider
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

        $this->updateMapList();

        $currentMap = $this->connection->getCurrentMapInfo();
        if ($currentMap) {
            $this->mapStorage->setCurrentMap($currentMap);
            try {
                $this->mapStorage->setNextMap($this->connection->getNextMapInfo());
            } catch (NextMapException $ex) {
                $this->mapStorage->setNextMap($currentMap);
            }
        }
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
            } catch (NextMapException $ex) {
                // this is if no maps defined
                return;
            }

            if (!empty($maps)) {
                foreach ($maps as $map) {
                    $this->mapStorage->addMap($map);
                }
            }

            $start += self::BATCH_SIZE;

        } while (count($maps) == self::BATCH_SIZE);
    }

    /**
     * Called when map list is modified.
     *
     * @param $curMapIndex
     * @param $nextMapIndex
     * @param $isListModified
     *
     */
    public function onMapListModified($curMapIndex, $nextMapIndex, $isListModified)
    {
        if ($isListModified) {
            $oldMaps = $this->mapStorage->getMaps();

            $this->mapStorage->resetMapData();
            $this->updateMapList();

            // We will dispatch even only when list is modified. If not we dispatch specific events.
            $this->dispatch(__FUNCTION__, [$oldMaps, $curMapIndex, $nextMapIndex, $isListModified]);
        }

        try {
            $currentMap = $this->connection->getCurrentMapInfo();  // sync better
        } catch (\Exception $e) {
            echo $e->getMessage(). "\n";

            $currentMap = null;
        }

        // current map can be false if map by index is not found..
        if ($currentMap) {
            if ($this->mapStorage->getCurrentMap()->uId != $currentMap->uId) {
                $previousMap = $this->mapStorage->getCurrentMap();
                $this->mapStorage->setCurrentMap($currentMap);

                $this->dispatch('onExpansionMapChange', [$currentMap, $previousMap]);
            }
        }

        try {
            $nextMap = $this->connection->getNextMapInfo();  // sync better
        } catch (\Exception $e) {
            echo $e->getMessage(). "\n";
            $nextMap = null;
        }
        // next map can be false if map by index is not found..
        if ($nextMap) {
            if ($this->mapStorage->getNextMap()->uId != $nextMap->uId) {
                $previousNextMap = $this->mapStorage->getNextMap();
                $this->mapStorage->setNextMap($nextMap);

                $this->dispatch('onExpansionNextMapChange', [$nextMap, $previousNextMap]);
            }
        }
    }
}
