<?php

namespace eXpansion\Bundle\LocalMapRatings\Model;

use Maniaplanet\DedicatedServer\Structures\Map;

class MapratingQueryBuilder
{

    /**
     * @param Map $map
     * @return Maprating[]
     */
    public function getRatingsForMap(Map $map)
    {
        $query = new MapratingQuery();
        $result = $query->filterByMapuid($map->uId)->find();

        $result->populateRelation('Player');

        return $result->getData();
    }
}
