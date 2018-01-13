<?php

namespace eXpansion\Bundle\Maps\Services;

use eXpansion\Bundle\Maps\Model\Map;
use eXpansion\Bundle\Maps\Model\Map\MapTableMap;
use eXpansion\Bundle\Maps\Model\MapQuery;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMaplist;
use Maniaplanet\DedicatedServer\Structures\Map as DedicatedMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Propel;

class MapInfoService implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyMap, ListenerInterfaceMpLegacyMaplist
{

    /**
     * @var AdminGroups
     */
    private $adminGroups;
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var Console
     */
    private $console;

    /**
     * JukeboxService constructor.
     * @param Console     $console
     * @param MapStorage  $mapStorage
     * @param AdminGroups $adminGroups
     */
    public function __construct(Console $console, MapStorage $mapStorage, AdminGroups $adminGroups)
    {

        $this->mapStorage = $mapStorage;
        $this->adminGroups = $adminGroups;
        $this->console = $console;
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {

    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        $this->syncMaps();
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // do nothing
    }

    /**
     * @param DedicatedMap $map
     *
     * @return mixed
     */
    public function onBeginMap(DedicatedMap $map)
    {
        // do nothing
    }

    /**
     * @param DedicatedMap $map
     *
     * @return void
     */
    public function onEndMap(DedicatedMap $map)
    {
      // do nothing
    }

    /**
     * @param DedicatedMap[] $oldMaps
     * @param string         $currentMapUid
     * @param string         $nextMapUid
     * @param bool           $isListModified
     * @return void
     */
    public function onMapListModified($oldMaps, $currentMapUid, $nextMapUid, $isListModified)
    {
        if ($isListModified) {
            $this->syncMaps();
        }
    }

    public function onExpansionMapChange($currentMap, $previousMap)
    {
        // do nothing
    }

    public function onExpansionNextMapChange($nextMap, $previousNextMap)
    {
       // do nothing
    }

    protected function syncMaps()
    {
        $this->console->writeln("Starting Database Map Sync...");

        $mapuids = [];
        $allMaps = [];
        foreach ($this->mapStorage->getMaps() as $map) {
            $mapuids[] = $map->uId;
            $allMaps[$map->uId] = $this->convertMap($map);
        }

        $x = 0;
        $maps = array_chunk($mapuids, 200);
        $mapsInDb = [];

        foreach ($maps as $uids) {
            $this->console->writeln("Processing chunk ".($x + 1)." of ".count($maps)."...");
            $mapQuery = new MapQuery();
            $mapQuery->filterByMapuid($uids, Criteria::IN);
            /** @var \eXpansion\Bundle\Maps\Model\Map[] $data */
            $data = $mapQuery->find()->getData();

            foreach ($data as $map) {
                $mapsInDb[] = $map->getMapuid();
            }
            $x++;
        }

        $con = Propel::getWriteConnection(MapTableMap::DATABASE_NAME);
        $con->beginTransaction();

        $diff = array_diff($mapuids, $mapsInDb);

        foreach ($diff as $uid) {
            $dbmap = new Map();
            $dbmap->fromArray($allMaps[$uid], TableMap::TYPE_FIELDNAME);
            $dbmap->save();

        }
        $con->commit();
        MapTableMap::clearInstancePool();

        $this->console->writeln("Sync done.");

    }

    /**
     * @param DedicatedMap $map
     * @return array
     */
    private function convertMap($map)
    {
        $outMap = (array)$map;
        $outMap["mapUid"] = $map->uId;

        return $outMap;

    }


}
