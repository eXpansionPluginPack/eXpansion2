<?php

namespace eXpansion\Bundle\LocalRecords\Model;

/**
 * Class RecordQueryBuilder
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\LocalRecords\Query
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

        return $query->find()->getData();
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

        return $query->find()->getData();
    }
}