<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

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

    public function onModeScriptCallback($eventName, $parameters)
    {
        $this->dispatch('onModeScriptCallback', [$eventName, $parameters]);
    }
}
