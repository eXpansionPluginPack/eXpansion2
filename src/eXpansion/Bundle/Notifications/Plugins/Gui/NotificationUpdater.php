<?php

namespace eXpansion\Bundle\Notifications\Plugins\Gui;

use eXpansion\Framework\Core\Plugins\Gui\ScriptVariableUpdateFactory;
use FML\Script\Builder;

class NotificationUpdater extends ScriptVariableUpdateFactory
{
    /**
     * Update
     * @param string $type
     * @param string $title title of the notification
     * @param string $message message part of notification
     * @param int    $timeout timeout in milliseconds
     */
    public function setNotification($type, $title, $message, $timeout, $group)
    {
        $toast = [
            "title" => $title,
            "message" => $message,
            "type" => $type,
            "duration" => "".$timeout,
        ];


        $this->updateValue('notification', Builder::getArray($toast, true));

    }
}
