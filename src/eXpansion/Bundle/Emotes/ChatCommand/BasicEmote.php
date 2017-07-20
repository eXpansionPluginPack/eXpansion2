<?php

namespace eXpansion\Bundle\Emotes\ChatCommand;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\ChatCommand\AbstractChatCommand;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Symfony\Component\Console\Input\InputInterface;


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

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var PlayerStorage */
    protected $playerStorage;

    /**
     * BasicEmote constructor.
     *
     * @param string $command The chat command
     * @param string $nbMessages The emote message to send
     * @param array $aliases
     * @param ChatNotification $chatNotification
     * @param PlayerStorage $playerStorage
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
        parent::__construct($command, $aliases);
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;

        for ($i = 1; $i <= $nbMessages; $i++) {
            $this->messages[] = "expansion_emotes.$command".$i;
        }
    }

    /**
     * @inheritdoc
     */
    public function execute($login, InputInterface $input)
    {
        $select = rand(0, count($this->messages) - 1);
        $message = $this->messages[$select];

        $nickName = $this->playerStorage->getPlayerInfo($login)->getNickName();
        $prefix = $this->chatNotification->getMessage('expansion_emotes.prefix', ['%nickname%' => $nickName]);
        $this->chatNotification->sendMessage($message, null, ['%prefix%' => $prefix]);
    }
}
