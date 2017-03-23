<?php
namespace eXpansion\Core\DataProviders\Listener;

use eXpansion\Core\DataProviders\PlayerDataProvider;
use eXpansion\Core\Storage\Data\Player;

/**
 * Interface PlayerDataListenerInterface for plugins using the MapDataProvider data provider.
 *
 * @see PlayerDataProvider
 */
interface MapDataListenerInterface
{
    public function onMapListModified($oldMaps, $currentMapUid, $nextMapUid);

    public function onExpansionMapChange($currentMap, $previousMap);

    public function onExpansionNextMapChange($nextMap, $previousNextMap);
}
