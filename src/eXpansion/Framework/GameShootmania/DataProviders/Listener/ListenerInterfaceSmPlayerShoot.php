<?php

namespace eXpansion\Framework\GameShootmania\DataProviders\Listener;

interface ListenerInterfaceSmPlayerShoot
{

    /**
     * @param string $login  login
     * @param int    $weapon indexes are: 1-Laser, 2-Rocket, 3-Nucleus, 5-Arrow
     * @return void
     */
    public function onShoot($login, $weapon);
}
