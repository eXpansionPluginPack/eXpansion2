<?php

namespace eXpansion\Bundle\LocalRecords\Model;

use eXpansion\Bundle\LocalRecords\Model\Base\Record as BaseRecord;

/**
 * Skeleton subclass for representing a row from the 'record' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Record extends BaseRecord
{
    /**
     * @inheritdoc
     */
    public function getCheckpoints()
    {
        $checkPoints = parent::getCheckpoints();

        return json_decode($checkPoints, true);
    }

    /**
     * @inheritdoc
     */
    public function setCheckpoints($v)
    {
        return parent::setCheckpoints(json_encode($v));
    }


}
