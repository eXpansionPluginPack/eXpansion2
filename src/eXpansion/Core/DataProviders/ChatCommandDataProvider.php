<?php

namespace eXpansion\Core\DataProviders;

use eXpansion\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Core\Services\ChatCommands;

class ChatCommandDataProvider extends AbstractDataProvider
{
    /** @var ChatCommands  */
    protected $chatCommands;

    /** @var ChatCommands  */
    protected $chatNotification;

    /**
     * ChatCommandDataProvider constructor.
     * @param $chatCommands
     */
    public function __construct(ChatCommands $chatCommands, ChatNotificationInterface $chatNotification)
    {
        $this->chatCommands = $chatCommands;
        $this->chatNotification = $chatNotification;
    }

    /**
     * @inheritdoc
     */
    public function registerPlugin($pluginId, $pluginService)
    {
        parent::registerPlugin($pluginId, $pluginService);

        $this->chatCommands->registerPlugin($pluginId, $pluginService);
    }

    /**
     * @inheritdoc
     */
    public function deletePlugin($pluginId)
    {
        parent::deletePlugin($pluginId);

        $this->chatCommands->deletePlugin($pluginId);
    }

    /**
     * Called when a player chats on the server.
     *
     * @param int $playerUid
     * @param string $login
     * @param string $text
     * @param bool $isRegisteredCmd
     */
    public function onPlayerChat($playerUid, $login, $text, $isRegisteredCmd = false)
    {
        if (!$isRegisteredCmd) {
            return;
        }

        $cmdAndArgs = explode(' ', $text, 2);
        $cmdTxt = substr($cmdAndArgs[0], 1);
        $parameter = count($cmdAndArgs) > 1 ? $cmdAndArgs[1] : '';

        // Internal dedicated serer command to ignore.
        if($cmdTxt === 'version') {
            return;
        }

        $command = $this->chatCommands->getChatCommand($cmdTxt);
        if ($command && $command->validate($login, $parameter)) {
            $command->execute($login, $command->parseParameters($parameter));
        } else {
            $this->chatNotification->sendMessage('expansion_core.chat_commands.wrong_chat', $login);
        }
    }
}