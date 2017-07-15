<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Unblack
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author reaby
 */
class Unblack extends AbstractConnectionCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('login', InputArgument::REQUIRED, 'Login of player to unblack.')
        );

    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'expansion_admin_chat.unblack.description';
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $playerLogin = $input->getArgument('login');

        $this->chatNotification->sendMessage(
            'expansion_admin_chat.unblack.msg',
            null,
            ['%admin%' => $nickName, '%player%' => $playerLogin]
        );

        $this->connection->unBlackList($playerLogin);
    }
}
