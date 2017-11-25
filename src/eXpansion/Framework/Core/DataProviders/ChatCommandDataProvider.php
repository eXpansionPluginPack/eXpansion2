<?php

namespace eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\DataProviders\Listener\ChatCommandInterface;
use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Helpers\ChatOutput;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Framework\Core\Services\ChatCommands;

use /** @noinspection PhpUndefinedClassInspection */
    Symfony\Component\Console\Exception\RuntimeException;

/**
 * Class ChatCommandDataProvider, provides execution instructions for chat commands.
 *
 * @package eXpansion\Framework\Core\DataProviders
 */
class ChatCommandDataProvider extends AbstractDataProvider
{
    /** @var ChatCommands */
    protected $chatCommands;

    /** @var ChatNotificationInterface */
    protected $chatNotification;

    /** @var ChatOutput */
    protected $chatOutput;

    /**
     * ChatCommandDataProvider constructor.
     * @param ChatCommands $chatCommands
     * @param ChatNotificationInterface $chatNotification
     * @param ChatOutput $chatOutput
     */
    public function __construct(
        ChatCommands $chatCommands,
        ChatNotificationInterface $chatNotification,
        ChatOutput $chatOutput
    ) {
        $this->chatCommands = $chatCommands;
        $this->chatNotification = $chatNotification;
        $this->chatOutput = $chatOutput;
    }

    /**
     * @inheritdoc
     */
    public function registerPlugin($pluginId, $pluginService)
    {
        parent::registerPlugin($pluginId, $pluginService);

        /** @var ChatCommandInterface|object $pluginService */
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
        // disable for server
        if ($playerUid === 0) {
            return;
        }

        if (!$isRegisteredCmd) {
            return;
        }

        $text = substr($text, 1);
        $cmdAndArgs = explode(' ', $text);

        // Internal dedicated server commands to ignore.
        if ($cmdAndArgs[0] === 'version' || $cmdAndArgs[0] === 'serverlogin') {
            return;
        }

        $message = 'expansion_core.chat_commands.wrong_chat';

        list($command, $parameter) = $this->chatCommands->getChatCommand($cmdAndArgs);
        /** @var AbstractChatCommand $command */
        if ($command) {
            $parameter = implode(" ", $parameter);
            $message = $command->validate($login, $parameter);
            if (empty($message)) {
                try {
                    $this->chatOutput->setLogin($login);
                    $message = $command->run($login, $this->chatOutput, $parameter);
                } catch (PlayerException $e) {
                    // Player exceptions are meant to be sent to players, and not crash or even be logged.
                    $this->chatNotification->sendMessage($e->getTranslatableMessage(), $login);
                }
            }
        }

        if (!empty($message)) {
            $this->chatNotification->sendMessage($message, $login);
        }
    }
}
