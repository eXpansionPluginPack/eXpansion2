<?php

namespace eXpansion\Framework\Core\DataProviders;

class ConsoleDataProvider extends AbstractDataProvider
{

    public function onConsoleMessage($message)
    {
        $this->dispatch(__FUNCTION__, [$message]);
    }

}

