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

    public function onApplicationInit()
    {
        $this->dispatch(__FUNCTION__, []);
    }

    public function onApplicationReady()
    {
        $this->dispatch(__FUNCTION__, []);
    }


    public function onApplicationStop()
    {
        $this->dispatch(__FUNCTION__, []);
    }
}
