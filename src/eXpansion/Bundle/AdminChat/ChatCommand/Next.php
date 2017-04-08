<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

/**
 * Class Restart
 *
 * @package eXpansion\Bundle\AdminChat\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class Next extends AbstractConnectionCommand
{
    /**
     * @param $login
     * @param $parameter
     *
     * @return void
     */
    public function execute($login, $parameter)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $this->chatNotification->sendMessage('expansion_admin_chat.next', null,['%nickname%' => $nickName]);
        $this->connection->nextMap();
    }
}
