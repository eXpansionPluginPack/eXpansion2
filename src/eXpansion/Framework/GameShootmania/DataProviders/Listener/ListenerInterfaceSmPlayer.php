<?php

namespace eXpansion\Framework\GameShootmania\DataProviders\Listener;

use eXpansion\Framework\GameShootmania\Structures\Landmark;

interface ListenerInterfaceSmPlayer
{


    /**
     * Callback sent when a player is hit.
     *
     * @param string $shooterLogin    login
     * @param string $victimLogin     login
     * @param int    $weapon          id of weapon: [1-laser, 2-rocket, 3-nucleus, 5-arrow]
     * @param int    $damage          amount damage done by hit
     * @param int    $points          amount of points scored by shooter
     * @param float  $distance        distance between 2 players
     * @param array  $shooterPosition position at level
     * @param array  $victimPosition  position at level
     * @return void
     */
    public function onPlayerHit(
        $shooterLogin,
        $victimLogin,
        $weapon,
        $damage,
        $points,
        $distance,
        $shooterPosition,
        $victimPosition
    );

    /**
     * Callback sent when a player is eliminated.
     * @param string $shooterLogin    login
     * @param string $victimLogin     login
     * @param int    $weapon          id of weapon: [1-laser, 2-rocket, 3-nucleus, 5-arrow]
     * @param int    $damage          amount damage done by hit
     * @param array  $shooterPosition position at level
     * @param array  $victimPosition  position at level
     * @return void
     */
    public function onArmorEmpty(
        $shooterLogin,
        $victimLogin,
        $weapon,
        $damage,
        $shooterPosition,
        $victimPosition
    );

    /**
     * Callback when pole is being captured
     *
     * @param array    $players
     * @param Landmark $landmark
     */
    public function onCapture(
        $players,
        Landmark $landmark
    );

    /**
     * Callback when player triggers sector
     *
     * @param string $login
     * @param string $sectorId
     */
    public function onPlayerTriggersSector(
        $login,
        $sectorId
    );

    /**
     *  Callback when player touches an object at level
     *
     * @param string $login
     * @param string $objectId
     * @param string $modelId
     * @param string $modelName
     */
    public function onPlayerTouchesObject(
        $login,
        $objectId,
        $modelId,
        $modelName
    );

    /**
     *  Callback when player touches an object at level
     *
     * @param string $login
     * @param string $objectId
     * @param string $modelId
     * @param string $modelName
     */
    public function onPlayerThrowsObject(
        $login,
        $objectId,
        $modelId,
        $modelName
    );


}
