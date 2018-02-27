<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 19.52
 */

namespace eXpansion\Framework\GameCurrencyBundle\Plugins\Gui;


use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\GameCurrencyBundle\Services\GameCurrencyService;
use eXpansion\Framework\Notifications\Services\Notifications;

class BillWindow extends WindowFactory
{
    /**
     * @var GameCurrencyService
     */
    private $currencyService;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    private $recipient = "";
    private $amount = "";
    /**
     * @var Notifications
     */
    private $notifications;


    /**
     * BillWindow constructor.
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WindowFactoryContext $context
     * @param GameCurrencyService  $currencyService
     * @param GameDataStorage      $gameDataStorage
     * @param Notifications        $notifications
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context,
        GameCurrencyService $currencyService,
        GameDataStorage $gameDataStorage,
        Notifications $notifications
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->currencyService = $currencyService;
        $this->gameDataStorage = $gameDataStorage;
        $this->notifications = $notifications;
    }

    public function setDetails($login, $amount)
    {
        $this->recipient = $login;
        $this->amount = $amount;
    }

    protected function createContent(ManialinkInterface $manialink)
    {

        $column1 = $this->uiFactory->createLayoutRow(0, 0, [], 1);
        $column1->addChildren([
            $this->uiFactory->createLabel("Recipient"),
            $this->uiFactory->createInput("login")->setDefault($this->recipient),
            $this->uiFactory->createLabel("Amount"),
            $this->uiFactory->createInput("amount")->setDefault($this->amount),
        ]);


        $actions = $this->uiFactory->createLayoutLine(0, 0, [], 3);
        $actions->addChildren([
            $this->uiFactory->createButton("Send")->setAction(
                $this->actionFactory->createManialinkAction($manialink, [$this, "callbackSend"], null)
            ),
            $this->uiFactory->createButton("Cancel")->setAction(
                $this->actionFactory->createManialinkAction($manialink, [$this, "callbackCancel"], null)
            ),
        ]);


        $column2 = $this->uiFactory->createLayoutRow(0, 0, [], 1);
        $column2->addChildren([
            $this->uiFactory->createLabel("Message"),
            $this->uiFactory->createTextbox("message", "...", 3)
                ->setWidth(50),
            $actions,
        ]);

        $manialink->addChild($this->uiFactory->createLayoutLine(0, 0, [$column1, $column2], 2));
    }

    /** @param ManialinkInterface|Window $manialink */
    public function callbackSend($manialink, $login, $entries, $args)
    {
        if (!is_numeric($entries['amount'])) {
            $this->notifications->error("Amount is not integer", [], "Error", 10500, $manialink->getUserGroup());

            return;
        }


        $serverLogin = $this->gameDataStorage->getSystemInfo()->serverLogin;
        $bill = $this->currencyService->createBill(
            $serverLogin,
            $entries['amount'],
            $entries['login'],
            $entries['message']
        );

        $this->setBusy($manialink, "Processing...");

        if ($bill == false) {
            $this->notifications->error("Error while processing planets transaction", [], "Error", 10500,
                $manialink->getUserGroup());
            $this->closeManialink($manialink);
        }

        $this->currencyService->sendBill(
            $bill,
            function () use ($manialink, $bill) {
                $this->notifications->info("Successfully payed ".$bill->getAmount()."p to ".$bill->getReceiverlogin(),
                    [], "Success", 3500, $manialink->getUserGroup());
                $this->closeManialink($manialink);
            },
            function ($status) use ($manialink) {
                $this->notifications->error("Server said: ".$status,
                    [], "Error", 10500, $manialink->getUserGroup());
                $this->closeManialink($manialink);
            }
        );
    }

    /** @param ManialinkInterface|Window $manialink */
    public function callbackCancel($manialink, $login, $entries, $args)
    {
        $this->closeManialink($manialink);
    }

}