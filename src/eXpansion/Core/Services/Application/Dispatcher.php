<?php

namespace eXpansion\Core\Services\Application;

use eXpansion\Core\Services\DataProviderManager;
use eXpansion\Core\Services\PluginManager;

/**
 * Class Dispatcher, dispatches events to the Data Providers.
 *
 * @package eXpansion\Core\Services\Application
 * @author Oliver de Cramer
 */
class Dispatcher implements DispatcherInterface
{
    /** @var DataProviderManager  */
    protected $dataProviderManager;

    /** @var PluginManager */
    protected $pluginManager;

    /**
     * Dispatcher constructor.
     *
     * @param DataProviderManager $dataProviderManager
     * @param PluginManager $pluginManager
     */
    public function __construct(DataProviderManager $dataProviderManager, PluginManager $pluginManager)
    {
        $this->dataProviderManager = $dataProviderManager;
        $this->pluginManager = $pluginManager;
    }

    /**
     * Init.
     */
    public function init()
    {
        $this->dataProviderManager->init();
    }

    /**
     * @inheritdoc
     */
    public function dispatch($event, $params)
    {
        $this->dataProviderManager->dispatch($event, $params);
    }
}