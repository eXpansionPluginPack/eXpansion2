<?php

namespace eXpansion\Framework\Core\DataProviders\Listener;

use eXpansion\Framework\Core\DataProviders\PlayerDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Interface for plugins using the MatchDataProvider data provider.
 *
 * @see MatchDataProvider
 */
interface ListenerInterfaceMpLegacyMap
{
    /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map);

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onEndMap(Map $map);

}
