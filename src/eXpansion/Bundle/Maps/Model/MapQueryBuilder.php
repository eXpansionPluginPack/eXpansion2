<?php

namespace eXpansion\Bundle\Maps\Model;

class MapQueryBuilder
{
    /**
     * @return Map[]
     */
    public function getAllMaps()
    {
        $mapQuery = new MapQuery();

        return $mapQuery->find()->getData();
    }
}
