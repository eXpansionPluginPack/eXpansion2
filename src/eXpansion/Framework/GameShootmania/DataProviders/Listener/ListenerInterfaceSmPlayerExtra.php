<?php
/**
 * Created by PhpStorm.
 * User: Käyttäjä
 * Date: 15.1.2018
 * Time: 17.24
 */

namespace eXpansion\Framework\GameShootmania\DataProviders\Listener;


use eXpansion\Framework\GameShootmania\Structures\Position;

interface ListenerInterfaceSmPlayerExtra
{

    /**
     * @param string $shooterLogin    Login of the player who shot
     * @param string $victimLogin     Login of the player who dodged
     * @param int    $weapon          Id of the weapon [1-Laser, 2-Rocket, 3-Nucleus, 5-Arrow]
     * @param float  $distance        Distance of the near miss
     * @param Position  $shooterPosition position in level
     * @param Position  $victimPosition  position in level
     * @return void
     */
    public function onNearMiss(
        $shooterLogin,
        $victimLogin,
        $weapon,
        $distance,
        Position $shooterPosition,
        Position $victimPosition
    );

    /**
     * @param string $shooterLogin
     * @param string $victimLogin
     * @param int    $shooterWeapon
     * @param int    $victimWeapon
     * @return void
     */
    public function onShotDeny($shooterLogin, $victimLogin, $shooterWeapon, $victimWeapon);

    /**
     * @param string $login
     * @return void
     */
    public function onFallDamage($login);


    /**
     * @param string $login
     * @return void
     */
    public function onRequestRespawn($login);

}


