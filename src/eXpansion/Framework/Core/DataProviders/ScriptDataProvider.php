<?php

namespace eXpansion\Framework\Core\DataProviders;

/**
 * Class ScriptDataProvider
 *
 * @package eXpansion\Framework\Core\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ScriptDataProvider extends AbstractDataProvider
{
    public function onModeScriptCallbackArray($eventName, $parameters)
    {
        $this->dispatch('onModeScriptCallbackArray', [$eventName, $parameters]);
    }
}
