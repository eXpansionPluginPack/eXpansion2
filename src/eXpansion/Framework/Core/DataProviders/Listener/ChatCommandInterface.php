<?php

namespace eXpansion\Framework\Core\DataProviders\Listener;


/**
 * Interface ChatCommandInterface
 *
 * @package eXpansion\Framework\Core\DataProviders\Listener;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
interface ChatCommandInterface
{
    /**
     * Get list of chat commands available.
     *
     * @return \eXpansion\Framework\Core\Model\ChatCommand\ChatCommandInterface[]
     */
    public function getChatCommands();
}