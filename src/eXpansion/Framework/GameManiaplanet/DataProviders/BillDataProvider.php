<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use Maniaplanet\DedicatedServer\Structures\Bill;

/**
 * VoteDataProvider provides vote information to plugins.
 *
 * @package eXpansion\Framework\Core\DataProviders
 * @author reaby
 */
class BillDataProvider extends AbstractDataProvider
{
    /**
     * @param int    $billId
     * @param int    $state
     * @param string $stateName
     * @param int    $transactionId
     */
    public function onBillUpdated($billId, $state, $stateName, $transactionId)
    {
        $bill = new Bill();
        $bill->stateName = $stateName;
        $bill->transactionId = (int)$transactionId;
        $bill->state = (int)$state;
        $this->dispatch(__FUNCTION__, [(int)$billId, $bill]);
    }
}
