<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Class ReasonUserCommand
 *
 * @author  Reaby
 * @package eXpansion\Bundle\AdminChat\ChatCommand
 */
class AdminReturnCommand extends AdminCommand
{

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $group = $this->getGroupLabel($login);
        try {
            $return = $this->connection->{$this->functionName}();

            $this->chatNotification->sendMessage(
                $this->chatMessage,
                $this->isPublic ? null : $login,
                ['%adminLevel%' => $group, '%admin%' => $nickName, '%return%' => $return]
            );
        } catch (\Exception $e) {
            $this->chatNotification->sendMessage(
                'expansion_admin_chat.dedicatedexception',
                 $login,
                ['%message%' => $e->getMessage()]
            );
        }
    }
}
