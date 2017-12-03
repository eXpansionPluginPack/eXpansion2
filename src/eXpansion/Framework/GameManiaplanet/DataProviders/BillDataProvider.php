<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * VoteDataProvider provides vote information to plugins.
 *
 * @package eXpansion\Framework\Core\DataProviders
 * @author reaby
 */
class BillDataProvider extends AbstractDataProvider
{
    /**
     * @param int $billId
     * @param int $state
     * @param string $stateName
     * @param int $transactionId
     */
    public function onBillUpdated($billId, $state, $stateName, $transactionId)
    {
        $this->dispatch(__FUNCTION__, [$billId, $state, $stateName, $transactionId]);
    }
}
