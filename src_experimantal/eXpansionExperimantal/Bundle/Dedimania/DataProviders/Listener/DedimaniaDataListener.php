<?php

namespace eXpansionExperimantal\Bundle\Dedimania\DataProviders\Listener;

use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaRecord;

/**
 * Interface DedimaniaDataListener
 *
 * @package eXpansionExperimantal\Bundle\Dedimania\DataProviders\Listener;
 * @author  reaby
 */
interface DedimaniaDataListener
{
    /**
     * Called when dedimania records are loaded.
     *
     * @param DedimaniaRecord[] $records
     */
    public function onDedimaniaRecordsLoaded($records);

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


    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerConnect(DedimaniaPlayer $player);

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerDisconnect(DedimaniaPlayer $player);


}