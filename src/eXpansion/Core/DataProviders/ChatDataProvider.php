<?php

namespace eXpansion\Core\DataProviders;


use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;

class ChatDataProvider extends AbstractDataProvider
{
    public function onPlayerChat($playerUid, $login, $text, $isRegisteredCmd = false)
    {
        if (!$isRegisteredCmd) {
            $this->dispatch(__FUNCTION__, [$login, $text]);
        }
    }
}