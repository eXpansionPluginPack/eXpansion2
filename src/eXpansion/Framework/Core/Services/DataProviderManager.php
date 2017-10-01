<?php

namespace eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use eXpansion\Framework\Core\Exceptions\DataProvider\UncompatibleException;
use eXpansion\Framework\Core\Model\ProviderListener;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DataProviderManager handles all the data providers.
 *
 * @TODO handle gamemode change.
 *
 * @package eXpansion\Framework\Core\Services
 */
class DataProviderManager
{
    /** For compatibility with every title/mode/script */
    const COMPATIBLE_ALL = "ALL";

    /** @var int[][][][]  List of providers by compatibility. */
    protected $providersByCompatibility = [];

    /** @var string[] Name of the provider for a service Id. */
    protected $providerById = [];

    /** @var string[] Interface a plugin needs extend/implement to be used by a provider. */
    protected $providerInterfaces = [];

    /** @var ProviderListener[][] Providers that listen a certain event. */
    protected $providerListeners = [];

    /** @var array[][] Enabled providers that listen to certain events. */
    protected $enabledProviderListeners = [];

    /** @var ContainerInterface */
    protected $container;

    /** @var GameDataStorage */
    protected $gameDataStorage;

    /** @var Console */
    protected $console;

    /**
     * DataProviderManager constructor.
     *
     * @param ContainerInterface $container
     * @param GameDataStorage $gameDataStorage
     * @param Console $console
     */
    public function __construct(ContainerInterface $container, GameDataStorage $gameDataStorage, Console $console)
    {
        $this->container = $container;
        $this->gameDataStorage = $gameDataStorage;
        $this->console = $console;
    }

    /**
     * Initialize all the providers properly.
     * @param PluginManager $pluginManager
     */
    public function init(PluginManager $pluginManager)
    {
        $this->reset($pluginManager);
    }

    /**
     * Reset
     * @param PluginManager $pluginManager
     */
    public function reset(PluginManager $pluginManager)
    {
        $title = $this->gameDataStorage->getTitle();
        $mode = $this->gameDataStorage->getGameModeCode();
        $script = $this->gameDataStorage->getGameInfos()->scriptName;

        foreach ($this->providersByCompatibility as $provider => $data) {

            $providerId = $this->getCompatibleProviderId($provider, $title, $mode, $script);

            if ($providerId) {
                $providerService = $this->container->get($providerId);

                if ($pluginManager->isPluginEnabled($providerId)) {
                    foreach ($this->providerListeners[$providerId] as $listener) {
                        $this->enabledProviderListeners[$listener->getEventName()][] = [
                            $providerService,
                            $listener->getMethod(),
                        ];
                    }
                }
            }
        }
    }

    /**
     * Register a provider.
     *
     * @param string $id
     * @param string $provider
     * @param string $interface
     * @param string[][] $compatibilities
     * @param string[] $listeners
     */
    public function registerDataProvider($id, $provider, $interface, $compatibilities, $listeners)
    {
        foreach ($compatibilities as $compatibility) {
            $this->providersByCompatibility[$provider][$compatibility['title']][$compatibility['mode']][$compatibility['script']] = $id;
        }

        foreach ($listeners as $eventName => $method) {
            $this->providerListeners[$id][] = new ProviderListener($eventName, $provider, $method);
        }
        $this->providerInterfaces[$provider] = $interface;
        $this->providerById[$id] = $provider;
    }

    /**
     * Check of a provider is compatible
     *
     * @param string $provider
     * @param string $title
     * @param string $mode
     * @param string $script
     *
     * @return bool
     */
    public function isProviderCompatible($provider, $title, $mode, $script)
    {
        return !is_null($this->getCompatibleProviderId($provider, $title, $mode, $script));
    }

    /**
     * @param string $provider
     * @param string $title
     * @param string $mode
     * @param string $script
     *
     * @return string|null
     */
    public function getCompatibleProviderId($provider, $title, $mode, $script)
    {
        $parameters = [
            [$provider, $title, $mode, $script],
            [$provider, $title, $mode, self::COMPATIBLE_ALL],
            [$provider, $title, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
            [$provider, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
        ];

        foreach ($parameters as $parameter) {
            $id = AssociativeArray::getFromKey($this->providersByCompatibility, $parameter);
            if (!is_null($id)) {
                return $id;
            }
        }

        return null;
    }

    /**
     * Register a plugin to the DataProviders.
     *
     * @param string $provider The provider to register the plugin to.
     * @param string $pluginId The id of the plugin to be registered.
     * @param string $title The title to register it for.
     * @param string $mode The mode to register it for.
     * @param string $script The script to register it for.
     *
     * @throws UncompatibleException
     */
    public function registerPlugin($provider, $pluginId, $title, $mode, $script)
    {
        $providerId = $this->getCompatibleProviderId($provider, $title, $mode, $script);

        /** @var AbstractDataProvider $providerService */
        $providerService = $this->container->get($providerId);
        $pluginService = $this->container->get($pluginId);
        $interface = $this->providerInterfaces[$provider];

        if ($pluginService instanceof $interface) {
            $this->deletePlugin($provider, $pluginId);
            $providerService->registerPlugin($pluginId, $pluginService);
        } else {
            throw new UncompatibleException("Plugin $pluginId isn't compatible with $provider. Should be instance of $interface");
        }

        $this->console->getConsoleOutput()->writeln("\t<info>- $provider : $providerId</info>");
    }

    /**
     * Provider to delete a plugin from.
     *
     * @param $provider
     * @param $pluginId
     *
     */
    public function deletePlugin($provider, $pluginId)
    {
        foreach ($this->providersByCompatibility[$provider] as $titleProviders) {
            foreach ($titleProviders as $modeProviders) {
                foreach ($modeProviders as $providerId) {
                    $providerService = $this->container->get($providerId);
                    $providerService->deletePlugin($pluginId);
                }
            }
        }
    }

    /**
     * Dispatch event to the data providers.
     *
     * @param $eventName
     * @param $params
     */
    public function dispatch($eventName, $params)
    {
        if (isset($this->enabledProviderListeners[$eventName])) {
            foreach ($this->enabledProviderListeners[$eventName] as $callback) {
                call_user_func_array($callback, $params);
            }
        }
    }
}
