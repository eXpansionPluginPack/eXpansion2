<?php


namespace eXpansion\Framework\GameCurrencyBundle\ChatCommand;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameCurrencyBundle\Services\GameCurrencyService;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;


/**
 * Class Donate
 *
 * @package eXpansion\Framework\GameCurrencyBundle\ChatCommand;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 * @author  reaby
 */
class Donate extends AbstractChatCommand
{
    /**
     * @var GameCurrencyService
     */
    protected $currencyService;

    /**
     * @var GameDataStorage
     */
    protected $gameDataStorage;

    /**
     * @var ChatNotification
     */
    protected $chatNotification;

    /**
     * @var PlayerStorage
     */
    protected $playerStorage;

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

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('amount', InputArgument::REQUIRED, "Amount to donate")
        );
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $amount = $input->getArgument("amount");
        $bill = $this->currencyService->createBill(
            $login,
            $amount,
            $this->gameDataStorage->getSystemInfo()->serverLogin,
            ""
        );

        if (!$bill) {
            $this->chatNotification->sendMessage(
                'expansion_game_currency.donate.error',
                $login,
                ['error' => "Bill could't be created"]
            );
            return;
        }

        $this->currencyService->sendBill(
            $bill,
            function () use ($bill, $login) {
                $player = $this->playerStorage->getPlayerInfo($login);
                $amount = $bill->getAmount();
                $this->chatNotification->sendMessage(
                    'expansion_game_currency.donate.success',
                    null,
                    ['amount' => $amount, 'nickname' => $player->getNickName()]
                );
            },
            function ($error) use ($login) {
                $this->chatNotification->sendMessage(
                    'expansion_game_currency.donate.error',
                    $login,
                    ['error' => $error]
                );
            }
        );
    }
}