<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use eXpansion\Framework\GameManiaplanet\DataProviders\BillDataProvider;
use Maniaplanet\DedicatedServer\Structures\Bill;

/**
 * Interface for plugins using the BillDataProvider.
 *
 * @see BillDataProvider
 * @author Reaby
 */
interface ListenerInterfaceMpLegacyBill
{

    /**
     * @param int  $billId
     * @param Bill $bill
     * @return void
     */
    public function onBillUpdated($billId, Bill $bill);
}
