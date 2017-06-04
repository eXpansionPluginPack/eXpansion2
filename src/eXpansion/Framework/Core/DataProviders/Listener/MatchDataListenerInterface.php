<?php
namespace eXpansion\Framework\Core\DataProviders\Listener;

use eXpansion\Framework\Core\DataProviders\PlayerDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Interface MatchDataListenerInterface for plugins using the MatchDataProvider data provider.
 *
 * @see MatchDataProvider
 */
interface MatchDataListenerInterface
{
    /**
     * @param Map $map
     *
     * @return mixed
     */
    public function onBeginMap(Map $map);

    /**
     * @param Map $map
     *
     * @return mixed
     */
    public function onEndMap(Map $map);
}
