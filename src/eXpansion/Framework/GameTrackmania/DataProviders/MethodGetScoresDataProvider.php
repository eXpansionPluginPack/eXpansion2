<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\ScriptMethods\AbstractScriptMethod;
use eXpansion\Framework\Core\DataProviders\MethodScriptDataProviderInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use Maniaplanet\DedicatedServer\Connection;


/**
 * Class MethodGetStoresDataProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\GameManiaplanet\DataProviders
 */
class MethodGetScoresDataProvider extends AbstractDataProvider implements MethodScriptDataProviderInterface
{
    /** @var Factory */
    protected $factory;

    /**
     * MethodGetScoresDataProvider constructor.
     *
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     *
     * @param string $pluginId
     * @param AbstractScriptMethod $pluginService
     */
    public function registerPlugin($pluginId, $pluginService)
    {
        parent::registerPlugin($pluginId, $pluginService);

        $pluginService->setCurrentDataProvider($this);
    }


    /**
     * Request call to fetch scores.
     *
     * @return void
     * @throws
     */
    public function request()
    {
        $this->factory->getConnection()->triggerModeScriptEvent("Trackmania.GetScores", ["responseid" => (string) time()]);
    }

    /**
     * Set scores.
     *
     * @param array $params
     */
    public function setScores($params)
    {
        $this->dispatch('set', [$params]);
    }
}
