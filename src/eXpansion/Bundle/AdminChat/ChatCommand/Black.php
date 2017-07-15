<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Black
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author reaby
 */
class Black extends AbstractConnectionCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('login', InputArgument::REQUIRED, 'Login of player to blacklist.')
        );
        $this->inputDefinition->addArgument(
            new InputArgument('reason', InputArgument::REQUIRED, 'The reason for blacklisting.')
        );
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'expansion_admin_chat.black.description';
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $playerLogin = $input->getArgument('login');
        $reason = $input->getArgument('reason');

        $playerNickName = $this->playerStorage->getPlayerInfo($playerLogin)->getNickName();

        $this->chatNotification->sendMessage(
            'expansion_admin_chat.black.msg',
            null,
            ['%admin%' => $nickName, '%player%' => $playerNickName, "%reason%" => $reason]
        );

        $this->connection->banAndBlackList($playerLogin, $reason);
    }
}
