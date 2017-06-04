<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Model\Plugin\PluginDescription;
use eXpansion\Framework\Core\Model\Plugin\PluginDescriptionFactory;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PluginManager handles all the plugins.
 *
 * @TODO handle gamemode change.
 *
 * @package eXpansion\Framework\Core\Services
 */
class PluginManager
{
    /** @var PluginDescription[] List of all the plugins adescriptions. */
    protected $plugins = [];

    /** @var PluginDescription[] Current List of enabled plugins */
    protected $enabledPlugins = [];

    /** @var PluginDescriptionFactory  */
    protected $pluginDescriptionFactory;

    /** @var ContainerInterface  */
    protected $container;

    /** @var DataProviderManager  */
    protected $dataProviderManager;

    /** @var GameDataStorage  */
    protected $gameDataStorage;

    /** @var Console  */
    protected $console;

    /**
     * PluginManager constructor.
     *
     * @param ContainerInterface       $container
     * @param PluginDescriptionFactory $pluginDescriptionFactory
     * @param DataProviderManager      $dataProviderManager
     * @param GameDataStorage          $gameDataStorage
     * @param Console                  $console
     */
    public function __construct(
        ContainerInterface $container,
        PluginDescriptionFactory $pluginDescriptionFactory,
        DataProviderManager $dataProviderManager,
        GameDataStorage $gameDataStorage,
        Console $console
    )
    {
        $this->container = $container;
        $this->pluginDescriptionFactory = $pluginDescriptionFactory;
        $this->dataProviderManager = $dataProviderManager;
        $this->gameDataStorage = $gameDataStorage;
        $this->console = $console;
    }

    /**
     * Initialize plugins.
     */
    public function init()
    {
        $this->reset();
    }

    public function reset()
    {
        $title = $this->gameDataStorage->getTitle();
        $mode = $this->gameDataStorage->getGameModeCode();
        $script = $this->gameDataStorage->getGameInfos()->scriptName;

        $this->enableDisablePlugins($title, $mode, $script);    }

    /**
     * Enable all possible plugins.
     *
     * @param string $title
     * @param string $mode
     * @param string $script
     */
    protected function enableDisablePlugins($title, $mode, $script)
    {
        $pluginsToEnable = [];
        $pluginsToProcess = $this->plugins;

        do {
            $lastEnabledPluginCount = count($pluginsToEnable);
            $pluginsToProcessNew = [];

            foreach ($pluginsToProcess

                     as $pluginId => $plugin) {
                if ($this->isPluginCompatible($plugin, $pluginsToEnable, $title, $mode, $script)) {
                    $pluginsToEnable[$pluginId] = $plugin;
                } else {
                    $pluginsToProcessNew[$pluginId] = $plugin;
                }
            }

            $pluginsToProcess = $pluginsToProcessNew;
        } while ($lastEnabledPluginCount != count($pluginsToEnable) && !empty($pluginsToProcess));

        foreach ($pluginsToEnable as $plugin) {
            $this->enablePlugin($plugin, $title, $mode, $script);
        }

        foreach ($pluginsToProcess as $plugin) {
            $this->disablePlugin($plugin);
        }
    }

    /**
     * Check if a plugin is compatible or not.
     *
     * @param PluginDescription $plugin
     * @param $enabledPlugins
     * @param $title
     * @param $mode
     * @param $script
     *
     * @return bool
     */
    protected function isPluginCompatible(PluginDescription $plugin, $enabledPlugins, $title, $mode, $script) {

        // first check for other plugins.
        foreach ($plugin->getParents() as $parentPluginId) {
            if (!isset($enabledPlugins[$parentPluginId])) {
                // A parent plugin is missing. Can't enable plugin.
                return false;
            }
        }

        // Now check  for data providers.
        foreach ($plugin->getDataProviders() as $dataProvider) {
            $providerId = $this->dataProviderManager->getCompatibleProviderId($dataProvider, $title, $mode, $script);

            if (is_null($providerId) || !isset($enabledPlugins[$providerId])) {
                // Either there are no data providers compatible or the only one compatible
                return false;
            }
        }

        // If data provider need to check if it was "the chosen one".
        if ($plugin->isIsDataProvider()) {
            $selectedProvider = $this->dataProviderManager->getCompatibleProviderId($plugin->getDataProviderName(), $title, $mode, $script);

            if ($plugin->getPluginId() != $selectedProvider) {
                // This data provider wasn't the one selected and therefore the plugin isn't compatible.
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

        if ($pluginService instanceof StatusAwarePluginInterface && !isset($this->enabledPlugins[$plugin->getPluginId()])) {
            $pluginService->setStatus(true);
        }

        $this->console->getConsoleOutput()
            ->writeln("<info>Plugin <comment>'{$plugin->getPluginId()}'</comment> is enabled with providers :</info>");
        foreach ($plugin->getDataProviders() as $provider) {
            $this->dataProviderManager->registerPlugin($provider, $plugin->getPluginId(), $title, $mode, $script);
        }

        $this->enabledPlugins[$plugin->getPluginId()] = $plugin;
    }

    /**
     * Disable a plugin.
     *
     * @param PluginDescription $plugin
     *
     */
    protected function disablePlugin(PluginDescription $plugin) {
        $plugin->setIsEnabled(false);
        $pluginService = $this->container->get($plugin->getPluginId());

        foreach ($plugin->getDataProviders() as $provider) {
            $this->dataProviderManager->deletePlugin($provider, $plugin->getPluginId());
        }

        if (isset($this->enabledPlugins[$plugin->getPluginId()])) {
            unset($this->enabledPlugins[$plugin->getPluginId()]);

            if ($pluginService instanceof StatusAwarePluginInterface) {
                $pluginService->setStatus(true);
            }
        }
    }

    /**
     * Check if a plugin is enabled or not.
     *
     * @param $pluginId
     *
     * @return bool
     */
    public function isPluginEnabled($pluginId) {
        return isset($this->enabledPlugins[$pluginId]);
    }

    /**
     * Register a plugin.
     *
     * @param string $id The service id of the plugin to register.
     * @param string[] $dataProviders The data providers it needs to work.
     * @param string[] $parents The parent plugins.
     */
    public function registerPlugin($id, $dataProviders, $parents, $dataProviderName = null) {
        if (!isset($this->plugins[$id])) {
            $this->plugins[$id] = $this->pluginDescriptionFactory->create($id);
        }

        $this->plugins[$id]->setDataProviders($dataProviders);
        $this->plugins[$id]->setParents($parents);
        $this->plugins[$id]->setDataProviderName($dataProviderName);
    }
}
