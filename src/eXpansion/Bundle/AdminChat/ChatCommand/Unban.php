<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Unban
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author reaby
 */
class Unban extends AbstractConnectionCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('login', InputArgument::REQUIRED, 'Login of player to unban.')
        );

    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'expansion_admin_chat.unban.description';
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $playerLogin = $input->getArgument('login');

        $this->chatNotification->sendMessage(
            'expansion_admin_chat.unban.msg',
            null,
            ['%admin%' => $nickName, '%player%' => $playerLogin]
        );

        $this->connection->unBan($playerLogin);
    }
}
