<?php
/**
 * Created by PhpStorm.
 * User: Käyttäjä
 * Date: 20.3.2017
 * Time: 17:29
 */

namespace eXpansion\Framework\Core\DataProviders\Listener;


interface TimerDataListenerInterface
{

    public function onPreLoop();
    public function onPostLoop();
    public function onEverySecond();

}
