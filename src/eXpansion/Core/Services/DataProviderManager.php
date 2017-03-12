<?php

namespace eXpansion\Core\Services;


use eXpansion\Core\DataProviders\AbstractDataProvider;
use eXpansion\Core\Model\ProviderListner;
use eXpansion\Core\Plugins\StatusAwarePluginInterface;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DataProviderManager
 *
 * @TODO check, comments exceptions...
 *
 * @package eXpansion\Core\Services
 */
class DataProviderManager
{

    const COMPATIBLE_ALL = "ALL";

    protected $providersByCompatibility = [];

    protected $providerById = [];

    /** @var ProviderListner[][] */
    protected $providerListeners = [];

    /** @var ProviderListner[][] */
    protected $enabledProviderListeners = [];

    /** @var  ContainerInterface */
    protected $container;

    /**
     * DataProviderManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function init()
    {
        // TODO run check in order not  to have same providers multiple times.

        // TODO get this data from the dedicated!
        $title = 'TMStadium@nadeo';
        $mode = 'script';
        $script = 'TimeAttack.script.txt';

        foreach ($this->providerListeners as $id => $listeners)
        {
            $providerService = $this->container->get($id);

            foreach ($listeners as $listener) {
                if ($this->isProviderCompatible($listener->getProvider(), $title, $mode, $script)) {
                    $this->enabledProviderListeners[$listener->getEventName()][] = [
                        $providerService,
                        $listener->getMethod()
                    ];
                }
            }
        }
    }

    public function registerDataProvider($id, $provider, $title, $mode, $script)
    {
        if (empty($title)) {
            $title = self::COMPATIBLE_ALL;
        }
        if (empty($mode)) {
            $mode = self::COMPATIBLE_ALL;
        }
        if (empty($script)) {
            $script = self::COMPATIBLE_ALL;
        }

        $this->providersByCompatibility[$provider][$title][$mode][$script] = $id;
        $this->providerById[$id] = $provider;
    }

    public function registerDataProviderListener($id, $eventName, $method)
    {
        $provider = $this->providerById[$id];

        $this->providerListeners[$id][] = new ProviderListner($eventName, $provider, $method);
    }

    public function isProviderCompatible($provider, $title, $mode, $script)
    {
        return !is_null($this->getCompatibleProviderId($provider, $title, $mode, $script));
    }

    public function getCompatibleProviderId($provider, $title, $mode, $script)
    {
        $parameters = [
            [$provider, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
            [$provider, $title, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
            [$provider, $title, $mode, self::COMPATIBLE_ALL],
            [$provider, $title, $mode, $script]
        ];

        foreach ($parameters as $parameter) {
            $id = AssociativeArray::getFromKey($this->providersByCompatibility, $parameter);
            if (!is_null($id)) {
                return $id;
            }
        }

        return null;
    }

    public function registerPlugin($provider, $pluginId,  $title, $mode, $script)
    {
        /** @var AbstractDataProvider $providerService */
        $providerService = $this->container->get($this->getCompatibleProviderId($provider, $title, $mode, $script));
        $pluginService = $this->container->get($pluginId);

        $providerService->registerPlugin($pluginId, $pluginService);
    }

    public function dispatch($eventName, $params)
    {
        if (isset($this->enabledProviderListeners[$eventName])) {
            foreach ($this->enabledProviderListeners[$eventName] as $callback) {
                call_user_func_array($callback, $params);
            }
        }
    }
}