<?php

namespace eXpansion\Bundle\LocalRecords\Model;

use eXpansion\Bundle\LocalRecords\Model\Map\RecordTableMap;

/**
 * Class RecordQueryBuilder
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package   eXpansion\Bundle\LocalRecords\Query
 */
class RecordQueryBuilder
{
    /**
     * Get records on a certain map.
     *
     * @param $mapUid
     * @param $nbLaps
     * @param $sort
     * @param $nbRecords
     *
     * @return Record[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getMapRecords($mapUid, $nbLaps, $sort, $nbRecords)
    {
        $query = new RecordQuery();
        $query->filterByMapuid($mapUid);
        $query->filterByNblaps($nbLaps);
        $query->orderByScore($sort);
        $query->limit($nbRecords);

        $result = $query->find();
        $result->populateRelation('Player');
        RecordTableMap::clearInstancePool();

        return $result;
    }

    /**
     * Get a players record on a certain map.
     *
     * @param $mapUid
     * @param $nbLaps
     * @param $logins
     *
     * @return Record[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function getPlayerMapRecords($mapUid, $nbLaps, $logins)
    {
        $query = new RecordQuery();
        $query->filterByMapuid($mapUid);
        $query->filterByNblaps($nbLaps);
        $query->filterByPlayerLogins($logins);

        $result = $query->find();
        $result->populateRelation('Player');
        RecordTableMap::clearInstancePool();

        return $result;
    }
}
