<?php

namespace eXpansion\Bundle\AdminChat\ChatCommand;

use Symfony\Component\Console\Input\InputInterface;
use Maniaplanet\DedicatedServer\Xmlrpc\Exception as DedicatedException;

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
        }  catch (DedicatedException $e) {
            $this->logger->error("Error on admin command", ["exception" => $e]);
            $this->chatNotification->sendMessage("expansion_admin_chat.dedicatedexception", $login,
                ["%message%" => $e->getMessage()]);
        }
    }
}
