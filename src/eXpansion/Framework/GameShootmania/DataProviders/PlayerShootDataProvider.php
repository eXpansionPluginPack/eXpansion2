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
     * @param $params
     */
    public function onShoot($params)
    {
        $this->dispatch(__FUNCTION__, [$params['shooter'], $params['weapon']]);
    }

}
