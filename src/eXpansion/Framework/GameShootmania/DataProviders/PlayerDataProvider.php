<?php

namespace eXpansion\Framework\GameShootmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\GameShootmania\Structures\Landmark;

/**
 * Class PlayerDataProvider provides information to plugins about what is going on with players.
 *
 * @package eXpansion\Framework\Core\DataProviders
 */
class PlayerDataProvider extends AbstractDataProvider
{

    /**
     * Callback sent when a player is hit.
     *
     * @param int    $time            server time when event occurred
     * @param string $shooterLogin    login
     * @param string $victimLogin     login
     * @param int    $weapon          id of weapon: [1-laser, 2-rocket, 3-nucleus, 5-arrow]
     * @param int    $damage          amount damage done by hit
     * @param int    $points          amount of points scored by shooter
     * @param float  $distance        distance between 2 players
     * @param array  $shooterPosition position at level
     * @param array  $victimPosition  position at level
     *
     */
    public function onPlayerHit(
        $time,
        $shooterLogin,
        $victimLogin,
        $weapon,
        $damage,
        $points,
        $distance,
        $shooterPosition,
        $victimPosition
    ) {
        $this->dispatch(__FUNCTION__, [
            $shooterLogin,
            $victimLogin,
            $weapon,
            $damage,
            $points,
            $distance,
            $shooterPosition,
            $victimPosition,
        ]);
    }

    /**
     * Callback sent when a player is eliminated.
     * @param int    $time
     * @param string $shooterLogin
     * @param string $victimLogin
     * @param int    $weapon
     * @param int    $damage
     * @param array  $shooterPosition
     * @param array  $victimPosition
     */
    public function onArmorEmpty(
        $time,
        $shooterLogin,
        $victimLogin,
        $weapon,
        $damage,
        $shooterPosition,
        $victimPosition
    ) {
        $this->dispatch(__FUNCTION__, [
            $shooterLogin,
            $victimLogin,
            $weapon,
            $damage,
            $shooterPosition,
            $victimPosition,
        ]);
    }


    /**
     *
     * @param int   $time
     * @param array $players
     * @param array $landmark
     */
    public function onCapture(
        $time,
        $players,
        $landmark
    ) {

        $landmarkObj = Landmark::fromArrayOfArray($landmark);

        $this->dispatch(__FUNCTION__, [
            $players,
            $landmarkObj,
        ]);
    }

    /**
     * @param int    $time
     * @param string $login
     * @param string $sectorId
     */
    public function onPlayerTriggersSector(
        $time,
        $login,
        $sectorId
    ) {
        $this->dispatch(__FUNCTION__, [
            $login,
            $sectorId,
        ]);
    }

    /**
     *
     * @param int    $time
     * @param string $login
     * @param string $objectId
     * @param string $modelId
     * @param string $modelName
     */
    public function onPlayerTouchesObject(
        $time,
        $login,
        $objectId,
        $modelId,
        $modelName
    ) {
        $this->dispatch(__FUNCTION__, [
            $login,
            $objectId,
            $modelId,
            $modelName,
        ]);
    }

    /**
     *
     * @param int    $time
     * @param string $login
     * @param string $objectId
     * @param string $modelId
     * @param string $modelName
     */
    public function onPlayerThrowsObject(
        $time,
        $login,
        $objectId,
        $modelId,
        $modelName
    ) {
        $this->dispatch(__FUNCTION__, [
            $login,
            $objectId,
            $modelId,
            $modelName,
        ]);
    }

}
