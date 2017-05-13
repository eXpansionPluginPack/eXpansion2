<?php

namespace eXpansion\Framework\Core\Model\ChatCommand;


/**
 * Class AbstractChatCommandPlugin
 *
 * @package eXpansion\Framework\Core\Model\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class ChatCommandPlugin implements \eXpansion\Framework\Core\DataProviders\Listener\ChatCommandInterface
{

    protected $chatCommands;

    /**
     * ChatCommands constructor.
     * @param $chatCommands
     */
    public function __construct(array $chatCommands)
    {
        $this->chatCommands = $chatCommands;
    }

    /**
     * Get list of chat commands available.
     *
     * @return \eXpansion\Framework\Core\Model\ChatCommand\ChatCommandInterface[]
     */
    public function getChatCommands()
    {
        return $this->chatCommands;
    }
}