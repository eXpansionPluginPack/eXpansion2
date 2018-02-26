<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 17.31
 */

namespace eXpansion\Framework\GameCurrencyBundle\Services;


use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\GameCurrencyBundle\Model\Gamecurrency;
use eXpansion\Framework\GameCurrencyBundle\Model\GameCurrencyQueryBuilder;
use eXpansion\Framework\GameCurrencyBundle\Plugins\GameCurrencyPlugin;
use eXpansion\Framework\GameCurrencyBundle\Structures\CurrencyEntry;
use Psr\Log\LoggerInterface;

class GameCurrencyService
{

    /**
     * @var GameCurrencyPlugin
     */
    private $currencyPlugin;
    /**
     * @var GameCurrencyQueryBuilder
     */
    private $queryBuilder;
    /**
     * @var Console
     */
    private $console;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * GameCurrency constructor.
     * @param GameCurrencyPlugin       $currencyPlugin
     * @param GameCurrencyQueryBuilder $queryBuilder
     * @param Console                  $console
     * @param LoggerInterface          $logger
     */
    public function __construct(
        GameCurrencyPlugin $currencyPlugin,
        GameCurrencyQueryBuilder $queryBuilder,
        Console $console,
        LoggerInterface $logger
    ) {

        $this->currencyPlugin = $currencyPlugin;
        $this->queryBuilder = $queryBuilder;
        $this->console = $console;
        $this->logger = $logger;
    }

    /**
     * Send bill to login
     * Server needs to have in-game currency for sending a bill.
     *
     * @param Gamecurrency $bill
     * @param callable     $onSuccess
     * @param callable     $onFailure
     * @return void
     */
    public function sendBill(Gamecurrency $bill, callable $onSuccess, callable $onFailure)
    {
        // return if the bill is not defined by creating one.
        if ($bill === false) {
            return;
        }

        $currencyEntry = new CurrencyEntry();
        $currencyEntry->setBill($bill);
        $currencyEntry->setSuccessCallback($onSuccess);
        $currencyEntry->setFailureCallback($onFailure);
        $this->currencyPlugin->sendBill($currencyEntry);

    }


    /**
     * Create a bill for sending it forward
     * @param $login
     * @param $amount
     * @param $receiver
     * @param $message
     * @return Gamecurrency|bool
     */
    public function createBill($login, $amount, $receiver, $message)
    {
        $currencyEntry = new Gamecurrency();
        $currencyEntry->setAmount($amount);
        $currencyEntry->setSenderlogin($login);
        $currencyEntry->setReceiverlogin($receiver);
        $currencyEntry->setMessage($message);
        $currencyEntry->setDatetime(new \DateTime());
        try {
            $currencyEntry->save();

            return $currencyEntry;

        } catch (\Exception $e) {
            $this->logger->error("Error while creating bill", ["exception" => $e]);
            $this->console->write('$f00 Fail.', true);

            return false;
        }
    }


}