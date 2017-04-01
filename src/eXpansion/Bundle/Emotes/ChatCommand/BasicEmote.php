<?php

namespace eXpansion\Bundle\Emotes\ChatCommand;

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

    /** @var Connection  */
    protected $connection;

    /**
     * BasicEmote constructor.
     *
     * @param $command The chat command
     * @param string $message The emote message to send
     * @param Connection $connection
     * @param array $aliases
     * @param bool $parametersAsArray
     */
    public function __construct(
        $command,
        $message,
        Connection $connection,
        array $aliases = [],
        $parametersAsArray = true
    ) {
        parent::__construct($command, $aliases, $parametersAsArray);
        $this->message = $message;
        $this->connection = $connection;
    }

    public function execute($login, $parameter)
    {
        // TODO use proper notificaiton service & translations.
        $this->connection->chatSendServerMessage($this->message);
    }
}