<?php

namespace eXpansion\Framework\GameCurrencyBundle\ChatCommand;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameCurrencyBundle\Plugins\Gui\BillWindow;
use eXpansion\Framework\GameCurrencyBundle\Services\GameCurrencyService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 *
 * @author  reaby
 */
class Donate extends AbstractChatCommand
{
    /** @var BillWindow */
    private $billWindow;
    /**
     * @var GameCurrencyService
     */
    private $currencyService;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    /**
     * @var ChatNotification
     */
    private $chatNotification;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * ScriptPanel constructor.
     *
     * @param                      $command
     * @param array                $aliases
     * @param GameCurrencyService  $currencyService
     * @param GameDataStorage      $gameDataStorage
     * @param ChatNotification     $chatNotification
     * @param PlayerStorage        $playerStorage
     */
    public function __construct(
        $command,
        array $aliases = [],
        GameCurrencyService $currencyService,
        GameDataStorage $gameDataStorage,
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage
    ) {
        parent::__construct($command, $aliases);
        $this->currencyService = $currencyService;
        $this->gameDataStorage = $gameDataStorage;
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
    }

    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('amount', InputArgument::REQUIRED, "amount to donate")
        );

    }


    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $amount = $input->getArgument("amount");
        $bill = $this->currencyService->createBill($login, $amount,
            $this->gameDataStorage->getSystemInfo()->serverLogin, "");
        $this->currencyService->sendBill($bill, function () use ($bill, $login) {
            $player = $this->playerStorage->getPlayerInfo($login);
            $amount = $bill->getAmount();
            $this->chatNotification->sendMessage('|info|{info}Server received {variable}'.$amount.'p {info}donation from {variable}'.$player->getNickName());
        }, function ($error) use ($login) {
            $this->chatNotification->sendMessage("|error|{info}Error while processing your donation: {error}".$error, $login);
        });
    }
}
