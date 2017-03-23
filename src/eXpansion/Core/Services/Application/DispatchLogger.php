<?php

namespace eXpansion\Core\Services\Application;

use eXpansion\Core\Services\Console;
use eXpansion\Core\Services\DataProviderManager;
use eXpansion\Core\Services\PluginManager;

/**
 * Class DispatchLogger, logs every dedicated server event.
 *
 * @package eXpansion\Core\Services\Application
 * @author Oliver de Cramer
 */
class DispatchLogger implements DispatcherInterface
{
    /** @var Console  */
    protected $console;

    /**
     * Dispatcher constructor.
     *
     * @param DataProviderManager $dataProviderManager
     * @param PluginManager $pluginManager
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /**
     * Init.
     */
    public function init()
    {
        // Nothing to do here.
    }

    /**
     * @inheritdoc
     */
    public function dispatch($event, $params)
    {
        $this->console->getConsoleOutput()->writeln("<info>$event");
        $this->console->getConsoleOutput()->writeln(print_r($params, true));
    }
}