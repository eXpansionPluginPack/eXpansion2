<?php

namespace eXpansion\Core\DataProviders\Listener;


/**
 * Interface ChatCommandInterface
 *
 * @package eXpansion\Core\DataProviders\Listener;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
interface ChatCommandInterface
{
    /**
     * Get list of chat commands available.
     *
     * @return \eXpansion\Core\Model\ChatCommand\ChatCommandInterface[]
     */
    public function getChatCommands();
}