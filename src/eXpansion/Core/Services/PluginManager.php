<?php

namespace eXpansion\Core\Services;

use eXpansion\Core\Model\Plugin\PluginDescription;
use eXpansion\Core\Model\Plugin\PluginDescriptionFactory;
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
    /** @var PluginDescription[] List of all the plugins adescriptions. */
    protected $plugins = [];

    /** @var PluginDescription[] List of all the root plugins. Plugin that depends of no plugins */
    protected $pluginsTree = [];

    /** @var PluginDescriptionFactory  */
    protected $pluginDescriptionFactory;

    /** @var ContainerInterface  */
    protected $container;

    /** @var DataProviderManager  */
    protected $dataProviderManager;

    /**
     * PluginManager constructor.
     *
     * @param ContainerInterface $container
     * @param DataProviderManager $dataProviderManager
     */
    public function __construct(
        ContainerInterface $container,
        PluginDescriptionFactory $pluginDescriptionFactory,
        DataProviderManager $dataProviderManager
    )
    {
        $this->container = $container;
        $this->pluginDescriptionFactory = $pluginDescriptionFactory;
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

        $this->createPluginTree();
        $this->enableDisablePlugins($this->pluginsTree, $title, $mode, $script);
    }

    /**
     * Recursively enable disable plugins taking into account dependencies.
     *
     * @param PluginDescription[] $pluginsTree
     * @param $title
     * @param $mode
     * @param $script
     * @param bool $isCompatible
     *
     */
    protected function enableDisablePlugins($pluginsTree, $title, $mode, $script, $isCompatible = true)
    {
        foreach ($pluginsTree as $plugin) {
            // If parent product isn't compatible then this plugin needs disabling.
            // if not check all children plugins to see which ones needs enabling.
            if ($isCompatible && $this->isPluginCompatible($plugin, $title, $mode, $script)) {
                $this->enablePlugin($plugin,  $title, $mode, $script);

                if (!empty($plugin->getChildrens())) {
                    $this->enableDisablePlugins($plugin->getChildrens(), $title, $mode, $script, true);
                }
            } else {
                $this->disablePlugin($plugin);

                if (!empty($plugin->getChildrens())) {
                    $this->enableDisablePlugins($plugin->getChildrens(), $title, $mode, $script, false);
                }
            }
        }
    }

    /**
     * Check if a plugin is compatible.
     *
     * @param PluginDescription $plugin
     * @param $title
     * @param $mode
     * @param $script
     *
     * @return bool
     */
    protected function isPluginCompatible(PluginDescription $plugin, $title, $mode, $script)
    {
        foreach ($plugin->getDataProviders() as $provider) {
            if (!$this->dataProviderManager->isProviderCompatible($provider, $title, $mode, $script)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Enable a plugin for a certain game mode.
     *
     * @param PluginDescription $plugin
     * @param $title
     * @param $mode
     * @param $script
     */
    protected function enablePlugin(PluginDescription $plugin, $title, $mode, $script) {
        $plugin->setIsEnabled(true);
        $pluginService = $this->container->get($plugin->getPluginId());

        if ($pluginService instanceof StatusAwarePluginInterface) {
            $pluginService->setStatus(true);
        }

        foreach ($plugin->getDataProviders() as $provider) {
            $this->dataProviderManager->registerPlugin($provider, $plugin->getPluginId(),  $title, $mode, $script);
        }
    }

    /**
     * Disable a plugin.
     *
     * @param PluginDescription $plugin
     *
     */
    protected function disablePlugin(PluginDescription $plugin){
        $plugin->setIsEnabled(false);

        foreach ($plugin->getDataProviders() as $provider) {
            $this->dataProviderManager->deletePlugin($provider, $plugin->getPluginId());
        }
    }

    /**
     * Create a plugin tree to handle plugin dependencies.
     */
    protected function createPluginTree()
    {
        // Inverse order so that we have get childrends from the plugins.
        $toRemove = [];
        foreach ($this->plugins as $plugin) {
            if(!empty($plugin->getParents())) {
                foreach ($plugin->getParents() as $parentId) {
                    if ($this->plugins[$parentId]) {
                        $this->plugins[$parentId]->addChildren($plugin);
                    } else {
                        $toRemove[] = $plugin->getPluginId();
                        break;
                    }
                }
            }
        }
        // TODO handle removed plugin, those plugins aren't just not compatible they are broken.

        // For now own we will hand plugins recusively.
        foreach ($this->plugins as $plugin) {
            if (empty($plugin->getParents())) {
                $this->pluginsTree[] = $plugin;
            }
        }
    }

    /**
     * Register a plugin.
     *
     * @param string $id The service id of the plugin to register.
     * @param string[] $dataProviders The data providers it needs to work.
     * @param string[] $parents The parent plugins.
     */
    public function registerPlugin($id, $dataProviders, $parents) {
        if (!isset($this->plugins[$id])) {
            $this->plugins[$id] = $this->pluginDescriptionFactory->create($id);
        }

        $this->plugins[$id]->setDataProviders($dataProviders);
        $this->plugins[$id]->setParents($parents);
    }
}
