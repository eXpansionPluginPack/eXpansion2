<?php

namespace eXpansion\Framework\Core\DataProviders;

/**
 * Class ScriptDataProvider
 *
 * @package eXpansion\Framework\Core\DataProviders;
 * @author reaby
 */
class ApplicationDataProvider extends AbstractDataProvider
{

    public function onApplicationInit($eventName, $parameters)
    {
        $this->dispatch(__FUNCTION__, [$eventName, $parameters]);
    }

    public function onApplicationReady($eventName, $parameters)
    {
        $this->dispatch(__FUNCTION__, [$eventName, $parameters]);
    }


    public function onApplicationStop($eventName, $parameters)
    {
        $this->dispatch(__FUNCTION__, [$eventName, $parameters]);
    }
}
