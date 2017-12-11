<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\ScriptBaseRounds;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\DataProviders\MethodScriptDataProviderInterface;
use eXpansion\Framework\Core\Storage\MapStorage;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class MethodGetNumberLapsDataProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\GameTrackmania\DataProviders
 */
class MethodGetNumberLapsDataProvider extends AbstractDataProvider implements MethodScriptDataProviderInterface
{
    /** @var Connection */
    protected $connection;

    /** @var MapStorage */
    protected $mapStorage;

    /**
     * MethodGetNumberLapsDataProvider constructor.
     *
     * @param Connection $connection
     * @param MapStorage $mapStorage
     */
    public function __construct(Connection $connection, MapStorage $mapStorage)
    {
        $this->connection = $connection;
        $this->mapStorage = $mapStorage;
    }


    /**
     * Request call to fetch something..
     *
     * @return void
     */
    public function request()
    {
        $scriptSettings = $this->connection->getModeScriptSettings();
        $currentMap = $this->mapStorage->getCurrentMap();

        $nbLaps = 1;
        if ($currentMap->lapRace) {
            $nbLaps = $currentMap->nbLaps;
        }

        if ($scriptSettings['S_ForceLapsNb'] != -1) {
            $nbLaps = $scriptSettings['S_ForceLapsNb'];
        }

        $this->dispatch('set', [$nbLaps]);
    }
}
