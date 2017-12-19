<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\ScriptBaseRounds;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\DataProviders\MethodScriptDataProviderInterface;
use eXpansion\Framework\Core\Storage\GameDataStorage;
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
    /** @var GameDataStorage */
    protected $gameDataStorage;

    /** @var MapStorage */
    protected $mapStorage;

    /**
     * MethodGetNumberLapsDataProvider constructor.
     *
     * @param GameDataStorage $gameDataStorage
     * @param MapStorage $mapStorage
     */
    public function __construct(GameDataStorage $gameDataStorage, MapStorage $mapStorage)
    {
        $this->gameDataStorage = $gameDataStorage;
        $this->mapStorage = $mapStorage;
    }

    /**
     * @inheritdoc
     *
     * @param string $pluginId
     * @param mixed $pluginService
     */
    public function registerPlugin($pluginId, $pluginService)
    {
        parent::registerPlugin($pluginId, $pluginService);

        $pluginService->setCurrentDataProvider($this);
    }

    /**
     * Request call to fetch something..
     *
     * @return void
     */
    public function request()
    {
        $scriptSettings = $this->gameDataStorage->getScriptOptions();
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
