<?php

namespace eXpansion\Bundle\Emotes\ChatCommand;

use eXpansion\Core\Helpers\ChatNotification;
use eXpansion\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Core\Storage\PlayerStorage;


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

    /** @var PlayerStorage */
    protected $playerStorage;

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
        PlayerStorage $playerStorage,
        array $aliases = [],
        $parametersAsArray = true
    ) {
        parent::__construct($command, $aliases, $parametersAsArray);
        $this->message = $message;
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
    }

    public function execute($login, $parameter)
    {
        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $this->chatNotification->sendMessage($this->message, null, ['%nickname%' => $nickName]);
    }
}