<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use eXpansion\Framework\GameManiaplanet\DataProviders\BillDataProvider;

/**
 * Interface for plugins using the BillDataProvider.
 *
 * @see BillDataProvider
 * @author Reaby
 */
interface ListenerInterfaceMpLegacyBill
{

    /**
     * @param int $billId
     * @param int $state
     * @param string $stateName
     * @param int $transactionId
     */
    public function onBillUpdated($billId, $state, $stateName, $transactionId);
}
