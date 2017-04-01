<?php

namespace eXpansion\Core\Model\ChatCommand;


/**
 * Class AbstractChatCommandPlugin
 *
 * @package eXpansion\Core\Model\ChatCommand;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
Class AbstractChatCommandPlugin implements \eXpansion\Core\DataProviders\Listener\ChatCommandInterface
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
     * @return \eXpansion\Core\Model\ChatCommand\ChatCommandInterface[]
     */
    public function getChatCommands()
    {
        return $this->chatCommands;
    }
}