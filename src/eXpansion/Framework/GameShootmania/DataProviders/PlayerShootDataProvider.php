<?php

namespace eXpansion\Framework\GameShootmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class PlayerDataProvider provides information to plugins about what is going on with players.
 *
 * @package eXpansion\Framework\Core\DataProviders
 */
class PlayerShootDataProvider extends AbstractDataProvider
{

    /**
     * @param int    $time   server time when event occurred
     * @param string $login  login
     * @param int    $weapon id of weapon: 1-laser, 2-rocket, 3-nucleus, 5-arrow
     */
    public function onShoot($time, $login, $weapon)
    {
        $this->dispatch(__FUNCTION__, [$login, $weapon]);
    }

}
