<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

/**
 * Interface ListenerInterfaceMpLegacyScript
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface ListenerInterfaceMpLegacyScript
{
    /**
     * @param string $eventName Name of the event.
     * @param mixed $parameters Parameters.
     *
     * @return void
     */
    public function onModeScriptCallbackArray($eventName, $parameters);
}
