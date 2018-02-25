<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 17.29
 */

namespace eXpansion\Framework\GameCurrencyBundle\Plugins;


use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\GameCurrencyBundle\Structures\CurrencyEntry;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyBill;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Bill;
use Psr\Log\LoggerInterface;

class GameCurrencyPlugin implements StatusAwarePluginInterface, ListenerInterfaceMpLegacyBill
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Console
     */
    private $console;

    /**
     * @var CurrencyEntry[]
     */
    protected $entries = [];
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;

    /**
     * GameCurrency constructor.
     * @param Connection      $connection
     * @param Console         $console
     * @param LoggerInterface $logger
     * @param GameDataStorage $gameDataStorage
     */
    public function __construct(
        Connection $connection,
        Console $console,
        LoggerInterface $logger,
        GameDataStorage $gameDataStorage
    ) {
        $this->connection = $connection;
        $this->logger = $logger;
        $this->console = $console;
        $this->gameDataStorage = $gameDataStorage;
    }


    /**
     * @param int  $billId
     * @param Bill $bill
     * @return void
     */
    public function onBillUpdated($billId, Bill $bill)
    {
        if (array_key_exists($billId, $this->entries)) {
            $entryBill = $this->entries[$billId]->getBill();
            $entryBill->setTransactionid($bill->transactionId);
            try {
                $entryBill->setStatus($bill->state)->save();
                $this->console->writeln('Status for bill '.$billId.' is now: $fff'.$bill->stateName);
            } catch (\Exception $e) {
                $this->logger->error("Error while saving bill", ["exception" => $e]);
            }
            if ($bill->state == Bill::STATE_PAYED) {
                call_user_func($this->entries[$billId]->getSuccessCallback());
                unset($this->entries[$billId]);
            }

            if ($bill->state == Bill::STATE_ERROR) {
                call_user_func($this->entries[$billId]->getFailureCallback());
                unset($this->entries[$billId]);
            }
        }
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        // TODO: Implement setStatus() method.
    }

    /**
     * @param CurrencyEntry $entry
     * @return void
     */
    public function sendBill(CurrencyEntry $entry)
    {

        try {
            $bill = $entry->getBill();


            if ($entry->getBill()->getSenderlogin() == $this->gameDataStorage->getSystemInfo()->serverLogin) {
                $this->console->write("Trying create a pay ".$bill->getAmount()."p to ".$bill->getReceiverlogin());
                $billId = $this->connection->pay(
                    $bill->getReceiverlogin(),
                    $bill->getAmount(),
                    $bill->getMessage());
            } else {
                $this->console->write("Trying to send a bill to ".$bill->getSenderlogin()." with ".$bill->getAmount()."p amount.. ");
                $billId = $this->connection->sendBill($bill->getSenderlogin(), $bill->getAmount(),
                    $bill->getReceiverlogin(), $bill->getMessage());
            }


            $entry->getBill()->setBillid($billId)->save();
            $this->entries[$billId] = $entry;

            $this->console->write('$0f0 Success.', true);

            return;
        } catch (\Exception $e) {
            $this->console->write(' $f00 Failed. $z'.$e->getMessage());
        }

        $this->console->write(' $f00 Failed. $z'.$e->getMessage());
    }

}