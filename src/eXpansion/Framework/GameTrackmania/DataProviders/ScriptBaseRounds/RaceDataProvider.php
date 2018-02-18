<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\ScriptBaseRounds;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Model\CompatibilityCheckDataProviderInterface;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class RaceDataProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
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
        if (!$map->lapRace) {
            return false;
        }

        $nbLaps = 1;
        if ($map->lapRace) {
            $nbLaps = $map->nbLaps;
        }

        $scriptSettings = $this->gameDataStorage->getScriptOptions();
        if ($scriptSettings['S_ForceLapsNb'] != -1) {
            $nbLaps = $scriptSettings['S_ForceLapsNb'];
        }

        // If rounds is configured to be single laps then no need for race data. lap is sufficient.
        return $nbLaps > 1;    }


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
