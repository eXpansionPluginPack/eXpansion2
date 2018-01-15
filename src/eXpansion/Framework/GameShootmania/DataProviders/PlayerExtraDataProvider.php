<?php

namespace eXpansion\Framework\GameShootmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class PlayerDataProvider provides information to plugins about what is going on with players.
 *
 * @package eXpansion\Framework\Core\DataProviders
 */
class PlayerExtraDataProvider extends AbstractDataProvider
{

    /**
     * @param $params
     */
    public function onNearMiss($params)
    {
        $this->dispatch(__FUNCTION__,
            [
                $params['shooter'],
                $params['victim'],
                $params['weapon'],
                $params['distance'],
                (object)$params['shooterposition'],
                (object)$params['victimposition'],
            ]);
    }

    /**
     * @param $params
     */
    public function onShotDeny($params)
    {
        $this->dispatch(__FUNCTION__,
            [$params['shooter'], $params['victim'], $params['shooterweapon'], $params['victimweapon']]);
    }


    /**
     * @param $params
     */
    public function onFallDamage($params)
    {
        $this->dispatch(__FUNCTION__, [$params['login']]);
    }

    /**
     * @param $params
     */
    public function onRequestRespawn($params) {
        $this->dispatch(__FUNCTION__, [$params['login']]);
    }


}
