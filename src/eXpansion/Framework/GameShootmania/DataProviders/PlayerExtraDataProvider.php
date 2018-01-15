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
     * @param int    $time
     * @param string $shooterLogin    Login of the player who shot
     * @param string $victimLogin     Login of the player who dodged
     * @param int    $weapon          Id of the weapon [1-Laser, 2-Rocket, 3-Nucleus, 5-Arrow]
     * @param float  $distance        Distance of the near miss
     * @param array  $shooterPosition position in level
     * @param array  $victimPosition  position in level
     */
    public function onNearMiss(
        $time,
        $shooterLogin,
        $victimLogin,
        $weapon,
        $distance,
        $shooterPosition,
        $victimPosition
    ) {
        $this->dispatch(__FUNCTION__,
            [$shooterLogin, $victimLogin, $weapon, $distance, $shooterPosition, $victimPosition]);
    }

    /**
     * @param int    $time
     * @param string $shooterLogin
     * @param string $victimLogin
     * @param int    $shooterWeapon
     * @param int    $victimWeapon
     */
    public function onShotDeny($time, $shooterLogin, $victimLogin, $shooterWeapon, $victimWeapon)
    {
        $this->dispatch(__FUNCTION__,
            [$shooterLogin, $victimLogin, $shooterWeapon, $victimWeapon]);
    }


    /**
     * @param int    $time
     * @param string $login
     */
    public function onFallDamage(
        $time,
        $login
    ) {
        $this->dispatch(__FUNCTION__,
            [$login]);
    }

    /**
     * @param int    $time
     * @param string $login
     */
    public function onRequestRespawn(
        $time,
        $login
    ) {
        $this->dispatch(__FUNCTION__,
            [$login]);
    }


}
