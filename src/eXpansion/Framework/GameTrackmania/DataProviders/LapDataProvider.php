<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders;
use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Model\CompatibilityCheckDataProviderInterface;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class LapDataProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\GameTrackmania\DataProviders
 */
class LapDataProvider extends AbstractDataProvider implements CompatibilityCheckDataProviderInterface
{
    /**
     * Check if data provider is compatible with current situation.
     *
     * @return bool
     */
    public function isCompatible(Map $map): bool
    {
        return $map->lapRace;
    }

    public function onWayPoint($params)
    {
        if ($params['isendlap']) {
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
    }
}