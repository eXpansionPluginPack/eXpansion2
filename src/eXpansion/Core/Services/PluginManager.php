<?php

namespace eXpansion\Core\Services;

use eXpansion\Core\Plugins\StatusAwarePluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PluginManager handles all the plugins.
 *
 * @TODO handle gamemode change.
 *
 * @package eXpansion\Core\Services
 */
class PluginManager
{
    /** @var string[][] List of all the plugins and the data provider they need. */
    protected $plugins = [];

    /** @var array List of plugins currently enabled */
    protected $enabledPlugins = [];

    /** @var  ContainerInterface */
    protected $container;

    /** @var DataProviderManager  */
    protected $dataProviderManager;

    /**
     * PluginManager constructor.
     *
     * @param ContainerInterface $container
     * @param DataProviderManager $dataProviderManager
     */
    public function __construct(ContainerInterface $container, DataProviderManager $dataProviderManager)
    {
        $this->container = $container;
        $this->dataProviderManager = $dataProviderManager;
    }

    /**
     * Initialize plugins.
     */
    public function init() {
        // TODO get this data from the dedicated!
        $title = 'TMStadium@nadeo';
        $mode = 'script';
        $script = 'TimeAttack.script.txt';

        foreach ($this->plugins as $pluginId => $providers) {
            $isCompatible = true;
            foreach ($providers as $provider) {
                if (!$this->dataProviderManager->isProviderCompatible($provider, $title, $mode, $script)) {
                    $isCompatible = false;
                    break;
                }
            }

            if ($isCompatible) {
                $this->enablePlugin($pluginId, $title, $mode, $script);
            }
        }
    }

    /**
     * Enable a plugin for a certain game mode.
     *
     * @param $pluginId
     * @param $title
     * @param $mode
     * @param $script
     */
    protected function enablePlugin($pluginId, $title, $mode, $script) {
        $this->enabledPlugins[$pluginId] = true;
        $pluginService = $this->container->get($pluginId);

        if ($pluginService instanceof StatusAwarePluginInterface) {
            $pluginId->setStatus(true);
        }

        foreach ($this->plugins[$pluginId] as $provider)
        {
            $this->dataProviderManager->registerPlugin($provider, $pluginId, $title, $mode, $script);
        }
    }

    /**
     * Register a plugin.
     *
     * @param string $id The service id of the plugin to register.
     * @param string $dataProvider The data provider it needs to work.
     */
    public function registerPlugin($id, $dataProvider) {
        $this->plugins[$id][] = $dataProvider;
    }
}