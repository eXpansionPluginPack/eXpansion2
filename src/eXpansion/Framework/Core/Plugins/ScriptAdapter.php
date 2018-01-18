<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyScript;
use eXpansion\Framework\Core\Services\Application\Dispatcher;

/**
 * Class ScriptAdapter
 *
 * @package eXpansion\Framework\Core\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ScriptAdapter implements ListenerInterfaceMpLegacyScript
{
    /** @var  Dispatcher */
    protected $dispatcher;

    /**
     * ScriptAdapter constructor.
     *
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $eventName  Name of the event.
     * @param mixed  $parameters Parameters.
     *
     * @return mixed
     */
    public function onModeScriptCallback($eventName, $parameters)
    {
        $parameters = $this->parseParameters($parameters);
        $this->dispatcher->dispatch($eventName, [$parameters]);
    }

    /**
     * @param string $eventName Name of the event.
     * @param mixed  $parameters Parameters.
     *
     * @return mixed
     */
    public function onModeScriptCallbackArray($eventName, $parameters)
    {
        $parameters = $this->parseParameters($parameters[0]);
        $this->dispatcher->dispatch($eventName, [$parameters]);
    }

    /**
     * Parse parameters that are usually in json.
     *
     * @param $parameters
     *
     * @return string|array
     */
    protected function parseParameters($parameters)
    {
        if (isset($parameters)) {
            $params = json_decode($parameters, true);
            if ($params) {
                return $params;
            }
        }

        // If json couldn't be encoded return string as it was
        return $parameters;
    }
}
