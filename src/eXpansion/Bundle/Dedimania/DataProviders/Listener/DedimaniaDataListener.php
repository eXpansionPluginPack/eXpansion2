<?php

namespace eXpansion\Bundle\Dedimania\DataProviders\Listener;

use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;

/**
 * Interface RecordsDataListener
 *
 * @package eXpansion\Bundle\Dedimania\DataProviders\Listener;
 * @author  reaby
 */
interface RecordsDataListener
{
    /**
     * Called when local records are loaded.
     *
     * @param DedimaniaRecord[] $records
     */
    public function onLocalRecordsLoaded($records);

    /**
     * @param DedimaniaRecord   $record
     * @param DedimaniaRecord   $oldRecord
     * @param DedimaniaRecord[] $records
     * @param  int              $position
     * @param  int              $oldPosition
     * @return void
     */
    public function onDedimaniaRecordsUpdate(
        DedimaniaRecord $record,
        DedimaniaRecord $oldRecord,
        $records,
        $position,
        $oldPosition
    );

}