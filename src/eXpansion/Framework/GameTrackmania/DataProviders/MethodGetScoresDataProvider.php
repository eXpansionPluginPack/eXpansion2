<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\DataProviders\MethodScriptDataProviderInterface;
use eXpansion\Framework\Core\ScriptMethods\AbstractScriptMethod;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class MethodGetStoresDataProvider
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\GameManiaplanet\DataProviders
 */
class MethodGetScoresDataProvider extends AbstractDataProvider implements MethodScriptDataProviderInterface
{
    /** @var Connection */
    protected $connection;

    /**
     * MethodGetStoresDataProvider constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
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
     * @return null
     */
    public function request()
    {
        $this->connection->triggerModeScriptEvent('TriggerModeScriptEventArray', ['Trackmania.GetScores'], [(string)time()]);
    }

    /**
     * Set scores.
     *
     * @param array $params
     */
    public function setScores($params)
    {
        $this->dispatch('setScores', [$params]);
    }
}
