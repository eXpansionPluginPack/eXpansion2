<?php

namespace eXpansion\Bundle\Dedimania\DataProviders;

use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class DedimaniaDataProvider
 *
 * @package eXpansion\Bundle\Dedimania\DataProviders;
 * @author  reaby
 */
class DedimaniaDataProvider extends AbstractDataProvider
{
    public function onDedimaniaRecordsLoaded($records)
    {
        $this->dispatch('onDedimaniaRecordsLoaded', [$records]);
    }

    public function onDedimaniaRecordsUpdate($params)
    {
        $this->dispatch(
            'onDedimaniaRecordsUpdate',
            [
                $params['record'],
                $params['record_old'],
                $params['records'],
                $params['position'],
                $params['position_old'],
            ]
        );
    }

}