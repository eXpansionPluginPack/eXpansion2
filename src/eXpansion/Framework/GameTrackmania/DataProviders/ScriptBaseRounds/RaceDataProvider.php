<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\ScriptBaseRounds;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Model\CompatibilityCheckDataProviderInterface;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class RaceDataProvider
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\GameTrackmania\DataProviders\ScriptBaseRounds
 */
class RaceDataProvider extends AbstractDataProvider implements CompatibilityCheckDataProviderInterface
{
    /**
     * Check if data provider is compatible with current situation.
     *
     * @return bool
     */
    public function isCompatible(Map $map): bool
    {
        return !$map->lapRace;
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
    }
}
