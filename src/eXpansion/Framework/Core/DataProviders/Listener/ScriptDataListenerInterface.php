<?php

namespace eXpansion\Framework\Core\DataProviders\Listener;

/**
 * Interface ScriptDataListenerInterface
 *
 * @package eXpansion\Framework\Core\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface ScriptDataListenerInterface
{
    /**
     * @param string $eventName Name of the event.
     * @param mixed $parameters Parameters.
     *
     * @return mixed
     */
    public function onModeScriptCallbackArray($eventName, $parameters);
}