<?php

namespace eXpansionExperimantal\Bundle\Dedimania\DataProviders;

use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class DedimaniaDataProvider
 *
 * @package eXpansionExperimantal\Bundle\Dedimania\DataProviders;
 * @author  reaby
 */
class DedimaniaDataProvider extends AbstractDataProvider
{
    /** @param DedimaniaRecord[] $records */
    public function onDedimaniaRecordsLoaded($records)
    {
        $this->dispatch('onDedimaniaRecordsLoaded', [$records]);
    }

    public function onDedimaniaRecordsUpdate($params)
    {
        $this->dispatch(
            'onDedimaniaRecordsUpdate',
            [
                $params[0],
                $params[1],
                $params[2],
                $params[3],
                $params[4],
            ]
        );
    }

    public function onDedimaniaPlayerConnect(DedimaniaPlayer $player)
    {
        $this->dispatch('onDedimaniaPlayerConnect', [$player]);
    }

    public function onDedimaniaPlayerDisconnect(DedimaniaPlayer $player)
    {
        $this->dispatch('onDedimaniaPlayerDisconnect', [$player]);
    }


}