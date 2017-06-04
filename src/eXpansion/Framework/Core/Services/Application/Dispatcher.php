<?php

namespace eXpansion\Framework\Core\Services\Application;

use eXpansion\Framework\Core\Services\DataProviderManager;
use eXpansion\Framework\Core\Services\PluginManager;

/**
 * Class Dispatcher, dispatches events to the Data Providers.
 *
 * @package eXpansion\Framework\Core\Services\Application
 * @author Oliver de Cramer
 */
class Dispatcher implements DispatcherInterface
{
    /** @var DataProviderManager  */
    protected $dataProviderManager;

    /** @var PluginManager */
    protected $pluginManager;

    /** @var EventProcessorInterface[] */
    protected $eventProcessors = [];

    /** @var bool  */
    protected $isInitialized = false;

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
        $this->pluginManager->init();
        $this->dataProviderManager->init($this->pluginManager);

        $this->isInitialized = true;
    }

    /**
     * Reset when game mode changes.
     */
    public function reset()
    {

    }

    /**
     * Add a processor of events.
     *
     * @param EventProcessorInterface $eventProcessor
     */
    public function addEventProcesseor(EventProcessorInterface $eventProcessor)
    {
        $this->eventProcessors[] = $eventProcessor;
    }

    /**
     * @inheritdoc
     */
    public function dispatch($event, $params)
    {
        foreach ($this->eventProcessors as $eventProcessor) {
            $eventProcessor->dispatch($event, $params);
        }

        if ($this->isInitialized) {
            $this->dataProviderManager->dispatch($event, $params);
        }
    }
}