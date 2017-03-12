<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 13:06
 */

namespace eXpansion\Core\DataProviders\Listener;


interface ChatDataListenerInterface
{
    public function onPlayerChat($login, $text);
}