<?php
namespace eXpansion\Framework\Core\DataProviders\Listener;

use eXpansion\Framework\Core\DataProviders\PlayerDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;

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
