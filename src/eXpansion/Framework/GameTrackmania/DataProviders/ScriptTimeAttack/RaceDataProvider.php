<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\ScriptTimeAttack;

use eXpansion\Framework\Core\Storage\GameDataStorage;
use \eXpansion\Framework\GameTrackmania\DataProviders\ScriptBaseRounds\RaceDataProvider as RoundRaceDataProvider;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class RaceDataProvider
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\GameTrackmania\DataProviders\ScriptBaseRounds
 */
class RaceDataProvider extends RoundRaceDataProvider
{
    /**
     * @var GameDataStorage
     */
    protected $gameDataStorage;

    /**
     * RaceDataProvider constructor.
     *
     * @param GameDataStorage $gameDataStorage
     */
    public function __construct(GameDataStorage $gameDataStorage)
    {
        $this->gameDataStorage = $gameDataStorage;
    }


    /**
     * Check if data provider is compatible with current situation.
     *
     * @return bool
     */
    public function isCompatible(Map $map): bool
    {
        return !$map->lapRace;
    }
}
