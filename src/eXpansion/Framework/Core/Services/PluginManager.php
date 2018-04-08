<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Model\Plugin\PluginDescription;
use eXpansion\Framework\Core\Model\Plugin\PluginDescriptionFactory;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use Maniaplanet\DedicatedServer\Structures\Map;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PluginManager handles all the plugins.
 *
 * @package eXpansion\Framework\Core\Services
 */
class PluginManager
{
    /** @var PluginDescription[] List of all the plugins adescriptions. */
    protected $plugins = [];

    /** @var PluginDescription[] Current List of enabled plugins */
    protected $enabledPlugins = [];

    /** @var PluginDescriptionFactory */
    protected $pluginDescriptionFactory;

    /** @var ContainerInterface */
    protected $container;

    /** @var DataProviderManager */
    protected $dataProviderManager;

    /** @var GameDataStorage */
    protected $gameDataStorage;

    /** @var Console */
    protected $console;

    /**
     * PluginManager constructor.
     *
     * @param ContainerInterface $container
     * @param PluginDescriptionFactory $pluginDescriptionFactory
     * @param DataProviderManager $dataProviderManager
     * @param GameDataStorage $gameDataStorage
     * @param Console $console
     */
    public function __construct(
        ContainerInterface $container,
        PluginDescriptionFactory $pluginDescriptionFactory,
        DataProviderManager $dataProviderManager,
        GameDataStorage $gameDataStorage,
        Console $console
    ) {
        $this->container = $container;
        $this->pluginDescriptionFactory = $pluginDescriptionFactory;
        $this->dataProviderManager = $dataProviderManager;
        $this->gameDataStorage = $gameDataStorage;
        $this->console = $console;
    }

    /**
     * Initialize.
     *
     * @throws \eXpansion\Framework\Core\Exceptions\DataProvider\UncompatibleException
     */
    public function init(Map $map)
    {
        $this->reset($map);
    }

    /**
     * Do a reset to plugins/
     *
     * @param Map $map
     *
     * @throws \eXpansion\Framework\Core\Exceptions\DataProvider\UncompatibleException
     */
    public function reset(Map $map)
    {
        $title = $this->gameDataStorage->getTitle();
        $mode = $this->gameDataStorage->getGameModeCode();
        $script = strtolower($this->gameDataStorage->getGameInfos()->scriptName);

        $this->enableDisablePlugins($title, $mode, $script, $map);
    }

    /**
     * @param $title
     * @param $mode
     * @param $script
     * @param Map $map
     *
     * @throws \eXpansion\Framework\Core\Exceptions\DataProvider\UncompatibleException
     */
    protected function enableDisablePlugins($title, $mode, $script, Map $map)
    {
        $pluginsToEnable = [];
        $pluginsToProcess = $this->plugins;

        do {
            $lastEnabledPluginCount = count($pluginsToEnable);
            $pluginsToProcessNew = [];

            foreach ($pluginsToProcess as $pluginId => $plugin) {
                if ($this->isPluginCompatible($plugin, $pluginsToEnable, $title, $mode, $script, $map)) {
                    $pluginsToEnable[$pluginId] = $plugin;
                } else {
                    $pluginsToProcessNew[$pluginId] = $plugin;
                }
            }

            $pluginsToProcess = $pluginsToProcessNew;
        } while ($lastEnabledPluginCount != count($pluginsToEnable) && !empty($pluginsToProcess));

        /* Enable plugins so that the data providers are propelry connected */
        $enableNotify = [];
        foreach ($pluginsToEnable as $pluginId => $plugin) {
            $enableNotify[$pluginId] = $this->enablePlugin($plugin, $title, $mode, $script, $map);
        }

        $disableNotify = [];
        foreach ($pluginsToProcess as $pluginId => $plugin) {
            $disableNotify[$pluginId] = $this->disablePlugin($plugin);
        }

        /* Once all is connected send status update */
        foreach ($enableNotify as $pluginId => $plugin) {
            if (!is_null($plugin)) {
                $this->console->getConsoleOutput()->writeln("<info>Enabling plugin : $pluginId</info>");
                if ($plugin instanceof StatusAwarePluginInterface) {
                    $plugin->setStatus(true);
                }
            }
        }
        foreach ($disableNotify as $pluginId => $plugin) {
            if (!is_null($plugin)) {
                $this->console->writeln("<info>Disabling plugin : $pluginId</info>");
                if ($plugin instanceof StatusAwarePluginInterface) {
                    $plugin->setStatus(false);
                }
            }
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
     * @param $map
     *
     * @return bool
     */
    protected function isPluginCompatible(PluginDescription $plugin, $enabledPlugins, $title, $mode, $script, Map $map)
    {

        // first check for other plugins.
        foreach ($plugin->getParents() as $parentPluginId) {
            if (!isset($enabledPlugins[$parentPluginId])) {
                // A parent plugin is missing. Can't enable plugin.
                return false;
            }
        }

        // Now check for data providers.
        foreach ($plugin->getDataProviders() as $dataProvider) {
            $dataProviders = explode("|", $dataProvider);
            $foundOne = false;

            foreach ($dataProviders as $provider) {
                $providerId = $this->dataProviderManager->getCompatibleProviderId($provider, $title, $mode, $script, $map);
                if (!is_null($providerId) && isset($enabledPlugins[$providerId])) {
                    // Either there are no data providers compatible or the only one compatible
                    $foundOne = true;
                    break;
                }
            }

            if (!$foundOne) {
                return false;
            }
        }

        // If data provider need to check if it was "the chosen one".
        if ($plugin->isIsDataProvider()) {
            $selectedProvider = $this->dataProviderManager->getCompatibleProviderId(
                $plugin->getDataProviderName(),
                $title,
                $mode,
                $script,
                $map
            );

            if ($plugin->getPluginId() != $selectedProvider) {
                // This data provider wasn't the one selected and therefore the plugin isn't compatible.
                return false;
            }
        }

        return true;
    }

    /**
     * Enable a certain plugin.
     *
     * @param PluginDescription $plugin
     * @param $title
     * @param $mode
     * @param $script
     * @param Map $map
     *
     * @return mixed
     *
     * @throws \eXpansion\Framework\Core\Exceptions\DataProvider\UncompatibleException
     */
    protected function enablePlugin(PluginDescription $plugin, $title, $mode, $script, Map $map)
    {
        $notify = false;
        $plugin->setIsEnabled(true);
        $pluginService = $this->container->get($plugin->getPluginId());

        if (!isset($this->enabledPlugins[$plugin->getPluginId()])) {
            $notify = true;
        }

        foreach ($plugin->getDataProviders() as $provider) {
            $dataProviders = explode("|", $provider);
            foreach ($dataProviders as $dataProvider) {
                $this->dataProviderManager->registerPlugin($dataProvider, $plugin->getPluginId(), $title, $mode, $script, $map);
            }
        }

        $this->enabledPlugins[$plugin->getPluginId()] = $plugin;

        return $notify ? $pluginService : null;
    }

    /**
     * Disable a plugin
     *
     * @param PluginDescription $plugin
     *
     * @return mixed
     */
    protected function disablePlugin(PluginDescription $plugin)
    {
        $notify = false;
        $plugin->setIsEnabled(false);
        $pluginService = $this->container->get($plugin->getPluginId());

        foreach ($plugin->getDataProviders() as $provider) {
            $dataProviders = explode("|", $provider);
            foreach ($dataProviders as $dataProvider) {
                $this->dataProviderManager->deletePlugin($dataProvider, $plugin->getPluginId());
            }
        }

        if (isset($this->enabledPlugins[$plugin->getPluginId()])) {
            unset($this->enabledPlugins[$plugin->getPluginId()]);

            $notify = true;
        }

        return $notify ? $pluginService : null;
    }

    /**
     * Check if a plugin is enabled or not.
     *
     * @param $pluginId
     *
     * @return bool
     */
    public function isPluginEnabled($pluginId)
    {
        return isset($this->enabledPlugins[$pluginId]);
    }

    /**
     * Register a plugin.
     *
     * @param string $id The service id of the plugin to register.
     * @param string[] $dataProviders The data providers it needs to work.
     * @param string[] $parents The parent plugins.
     */
    public function registerPlugin($id, $dataProviders, $parents, $dataProviderName = null)
    {
        if (!isset($this->plugins[$id])) {
            $this->plugins[$id] = $this->pluginDescriptionFactory->create($id);
        }

        $this->plugins[$id]->setDataProviders($dataProviders);
        $this->plugins[$id]->setParents($parents);
        $this->plugins[$id]->setDataProviderName($dataProviderName);
    }
}
