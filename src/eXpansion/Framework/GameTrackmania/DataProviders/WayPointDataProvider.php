<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;

/**
 * Class WayPointDataProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\GameTrackmania\DataProviders
 */
class WayPointDataProvider extends AbstractDataProvider
{
    public function onWayPoint($params)
    {
        $this->dispatch(
            'onPlayerWayPoint',
            [
                $params['login'],
                $params['time'],
                $params['racetime'],
                $params['laptime'],
                $params['stuntsscore'],
                $params['checkpointinrace'],
                $params['checkpointinlap'],
                $params['curracecheckpoints'],
                $params['curlapcheckpoints'],
                $params['blockid'],
                $params['speed'],
                $params['distance'],
            ]
        );
    }
}