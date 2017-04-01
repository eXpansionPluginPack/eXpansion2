<?php

namespace eXpansion\Bundle\Emotes\ChatCommand;

use eXpansion\Core\Helpers\ChatNotification;
use eXpansion\Core\Model\ChatCommand\AbstractChatCommand;
use Maniaplanet\DedicatedServer\Connection;


/**
 * Class BasicEmote to handle basic emote chat oommands.
 *
 * @package eXpansion\Bundle\Emotes\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class BasicEmote extends AbstractChatCommand
{
    /** @var string */
    protected $message;

    /** @var ChatNotification  */
    protected $chatNotification;

    /**
     * BasicEmote constructor.
     *
     * @param string $command The chat command
     * @param string $message The emote message to send
     * @param ChatNotification $chatNotification
     * @param array $aliases
     * @param bool $parametersAsArray
     */
    public function __construct(
        $command,
        $message,
        ChatNotification $chatNotification,
        array $aliases = [],
        $parametersAsArray = true
    ) {
        parent::__construct($command, $aliases, $parametersAsArray);
        $this->message = $message;
        $this->chatNotification = $chatNotification;
    }

    public function execute($login, $parameter)
    {
        $this->chatNotification->sendMessage($this->message, null, ['%nickname%' => $login]);
    }
}