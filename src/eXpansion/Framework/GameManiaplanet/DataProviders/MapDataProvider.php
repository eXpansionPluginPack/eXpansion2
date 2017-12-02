<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * MapDataProvider provides chat information to plugins.
 *
 * @package eXpansion\Framework\Core\DataProviders
 */
class MapDataProvider extends AbstractDataProvider
{

    public function onBeginMap($map)
    {
        $this->dispatch(__FUNCTION__, [Map::fromArray($map)]);
    }

    public function onEndMap($map)
    {

        $this->dispatch(__FUNCTION__, [Map::fromArray($map)]);
    }
}
