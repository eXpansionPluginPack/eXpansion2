<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Class Restart
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class SetServerName extends AbstractConnectionCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();

        $this->inputDefinition->addArgument(
            new InputArgument('name', InputArgument::REQUIRED, 'New name to give to the server')
        );
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'expansion_admin_chat.set_server_name.description';
    }


    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $name = $input->getArgument('name');

        $this->chatNotification->sendMessage(
            'expansion_admin_chat.set_server_name.msg',
            null,
            ['%nickname%' => $nickName, 'servername' => $name]
        );
        $this->connection->setServerName($name);
    }
}
