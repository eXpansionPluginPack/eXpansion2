<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 10:46
 */

namespace eXpansion\Core\Services;

use eXpansion\Core\Plugins\StatusAwarePluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PluginManager
 *
 * @TODO check, comments exceptions...
 *
 * @package eXpansion\Core\Services
 */
class PluginManager
{
    protected $plugins = [];

    protected $enabledPlugins = [];

    /** @var  ContainerInterface */
    protected $container;

    /** @var DataProviderManager  */
    protected $dataProviderManager;

    /**
     * PluginManager constructor.
     *
     * @param DataProviderManager
     */
    public function __construct(ContainerInterface $container, DataProviderManager $dataProviderManager)
    {
        $this->container = $container;
        $this->dataProviderManager = $dataProviderManager;
    }

    public function init() {
        // TODO get this data from the dedicated!
        $title = 'TMStadium@nadeo';
        $mode = 'script';
        $script = 'TimeAttack.script.txt';

        foreach ($this->plugins as $pluginId => $providers) {
            $isCompatible = true;
            foreach ($providers as $provider) {
                if(!$this->dataProviderManager->isProviderCompatible($provider, $title, $mode, $script)) {
                    $isCompatible = false;
                    break;
                }
            }

            if ($isCompatible) {
                $this->enablePlugin($pluginId, $title, $mode, $script);
            }
        }
    }

    protected function enablePlugin($pluginId, $title, $mode, $script) {
        $this->enabledPlugins[$pluginId] = true;
        $pluginService = $this->container->get($pluginId);

        if ($pluginService instanceof StatusAwarePluginInterface) {
            $pluginId->setStatus(true);
        }

        foreach ($this->plugins[$pluginId] as $provider)
        {
            $this->dataProviderManager->registerPlugin($provider, $pluginId,  $title, $mode, $script);
        }
    }

    public function registerPlugin($id, $dataProvider) {
        $this->plugins[$id][] = $dataProvider;
    }
}