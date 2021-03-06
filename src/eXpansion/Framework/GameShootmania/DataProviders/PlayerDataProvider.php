<?php

namespace eXpansion\Framework\GameShootmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\GameShootmania\Structures\Landmark;
use eXpansion\Framework\GameShootmania\Structures\Position;

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
     * @param $params
     */
    public function onPlayerHit($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['shooter'],
            $params['victim'],
            $params['weapon'],
            $params['damage'],
            $params['points'],
            $params['distance'],
            Position::fromArray($params['shooterposition']),
            Position::fromArray($params['victimposition']),
        ]);
    }

    /**
     * Callback sent when a player is eliminated.
     *
     * @param $params
     */
    public function onArmorEmpty($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['shooter'],
            $params['victim'],
            $params['weapon'],
            $params['distance'],
            Position::fromArray($params['shooterposition']),
            Position::fromArray($params['victimposition']),
        ]);
    }


    /**
     * @param $params
     */
    public function onCapture($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['players'],
            Landmark::fromArray($params['landmark']),
        ]);
    }

    /**
     * @param $params
     */
    public function onPlayerTriggersSector($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['login'],
            $params['sectorid'],
        ]);
    }

    /**
     *
     * @param $params
     */
    public function onPlayerTouchesObject($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['login'],
            $params['objectid'],
            $params['modelid'],
            $params['modelname'],
        ]);
    }

    /**
     *
     * @param $params
     */
    public function onPlayerThrowsObject($params)
    {
        $this->dispatch(__FUNCTION__, [
            $params['login'],
            $params['objectid'],
            $params['modelid'],
            $params['modelname'],
        ]);
    }

}
