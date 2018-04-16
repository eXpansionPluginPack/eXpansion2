<?php

namespace eXpansion\Framework\GameCurrencyBundle\ChatCommand;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\AdminGroups\Model\AbstractAdminChatCommand;
use eXpansion\Framework\GameCurrencyBundle\Plugins\Gui\BillWindow;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 *
 * @author  reaby
 */
class SendPlanets extends AbstractAdminChatCommand
{
    /** @var BillWindow */
    private $billWindow;

    /**
     * ScriptPanel constructor.
     *
     * @param                      $command
     * @param                      $permission
     * @param array                $aliases
     * @param BillWindow           $billWindow
     * @param AdminGroups          $adminGroups
     */
    public function __construct(
        $command,
        $permission,
        array $aliases = [],
        BillWindow $billWindow,
        AdminGroups $adminGroups
    ) {
        parent::__construct($command, $permission, $aliases, $adminGroups);
        $this->billWindow = $billWindow;
    }

    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('login', InputArgument::OPTIONAL, "Login to send")
        );
        $this->inputDefinition->addArgument(
            new InputArgument('amount', InputArgument::OPTIONAL, "amount to send")
        );

    }


    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $login2 = "";
        $amount = 0;
        if ($input->hasArgument("login")) {
            $login2 = $input->getArgument("login");
        }
        if ($input->hasArgument("amount")) {

            $amount = $input->getArgument("amount");
        }

        $this->billWindow->setDetails($login2, $amount);
        $this->billWindow->create($login);
    }
}
