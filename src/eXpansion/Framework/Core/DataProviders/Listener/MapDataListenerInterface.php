<?php

namespace eXpansion\Framework\Core\DataProviders\Listener;

use eXpansion\Framework\Core\DataProviders\PlayerDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Interface PlayerDataListenerInterface for plugins using the MapDataProvider data provider.
 *
 * @see PlayerDataProvider
 */
interface MapDataListenerInterface
{
    /**
     * @param Map[] $oldMaps
     * @param string $currentMapUid
     * @param string $nextMapUid
     * @param bool $isListModified
     * @return mixed
     */
    public function onMapListModified($oldMaps, $currentMapUid, $nextMapUid, $isListModified);

    public function onExpansionMapChange($currentMap, $previousMap);

    public function onExpansionNextMapChange($nextMap, $previousNextMap);
}
