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
    /** @var string[] */
    protected $messages;

    /** @var ChatNotification  */
    protected $chatNotification;

    /** @var PlayerStorage */
    protected $playerStorage;

    /**
     * BasicEmote constructor.
     *
     * @param string $command The chat command
     * @param string $nbMessages The emote message to send
     * @param ChatNotification $chatNotification
     * @param array $aliases
     * @param bool $parametersAsArray
     */
    public function __construct(
        $command,
        $nbMessages,
        array $aliases = [],
        ChatNotification $chatNotification,
        PlayerStorage $playerStorage,
        $parametersAsArray = true
    ) {
        parent::__construct($command, $aliases, $parametersAsArray);
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;

        for ($i = 1; $i <= $nbMessages; $i++) {
            $this->messages[] = "expansion_emotes.$command".$i;
        }
    }

    public function execute($login, $parameter)
    {
        $select = rand(0, count($this->messages) - 1);
        $message = $this->messages[$select];

        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $this->chatNotification->sendMessage($message, null, ['%nickname%' => $nickName]);
    }
}