<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Kick
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author reaby
 */
class Kick extends AbstractConnectionCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('login', InputArgument::REQUIRED, 'Login of player to kick.')
        );
        $this->inputDefinition->addArgument(
            new InputArgument('reason', InputArgument::REQUIRED, 'The reason for kicking.')
        );
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'expansion_admin_chat.kick.description';
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
            'expansion_admin_chat.kick.msg',
            null,
            ['%admin%' => $nickName, '%player%' => $playerNickName, "%reason%" => $reason]
        );

        $this->connection->kick($playerLogin, $reason);
    }
}
