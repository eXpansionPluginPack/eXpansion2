<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Storage\MapStorage;


/**
 * Class RaceDataProvider
 *
 * @package eXpansion\Framework\GameTrackmania\DataProviders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RaceDataProvider extends AbstractDataProvider
{
    /** @var MapStorage */
    protected $mapStorage;

    /**
     * RaceDataProvider constructor.
     *
     * @param MapStorage $mapStorage
     */
    public function __construct(MapStorage $mapStorage)
    {
        $this->mapStorage = $mapStorage;
    }


    public function onWayPoint($params)
    {
        if ($params['isendrace']) {
            $this->dispatch(
                'onPlayerEndRace',
                [
                    $params['login'],
                    $params['time'],
                    $params['racetime'],
                    $params['stuntsscore'],
                    $params['checkpointinrace'],
                    $params['curracecheckpoints'],
                    $params['blockid'],
                    $params['speed'],
                    $params['distance'],
                ]
            );
        }

        if ($params['isendlap'] && $this->mapStorage->getCurrentMap()->lapRace) {
            $this->dispatch(
                'onPlayerEndLap',
                [
                    $params['login'],
                    $params['time'],
                    $params['laptime'],
                    $params['stuntsscore'],
                    $params['checkpointinlap'],
                    $params['curlapcheckpoints'],
                    $params['blockid'],
                    $params['speed'],
                    $params['distance'],
                ]
            );
        }

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
