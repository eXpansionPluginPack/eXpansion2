<?php

namespace eXpansion\Framework\Core\Services\Application;

use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DataProviderManager;
use eXpansion\Framework\Core\Services\PluginManager;

/**
 * Class DispatchLogger, logs every dedicated server event.
 *
 * @package eXpansion\Framework\Core\Services\Application
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