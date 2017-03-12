<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 11:28
 */

namespace eXpansion\Core\Plugins;


use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;

class TotoPlugin implements ChatDataListenerInterface
{
    public function onPlayerChat($login, $text) {
        echo "[$login]$text\n";
    }
}