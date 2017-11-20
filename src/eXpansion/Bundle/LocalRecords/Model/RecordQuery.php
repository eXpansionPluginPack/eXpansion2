<?php

namespace eXpansion\Bundle\LocalRecords\Model;

use eXpansion\Bundle\LocalRecords\Model\Base\RecordQuery as BaseRecordQuery;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for performing query and update operations on the 'record' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class RecordQuery extends BaseRecordQuery
{
    /**
     * Filter by player logins
     *
     * @param string[] $logins
     */
    public function filterByPlayerLogins($logins)
    {
        $query = $this->joinWithPlayer();
        $query->filterBy('login', $logins, Criteria::IN);
    }
}
