<?php

namespace eXpansion\Framework\Config\Services;

use eXpansion\Framework\Config\Exception\UnhandledConfigurationException;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Ui\UiInterface;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;

use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class ConfigManager
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Services
 */
class ConfigManager
{
    /** @var ConfigInterface */
    protected $configurations = [];

    /** @var string[] */
    protected $configurationIds = [];

    /** @var AssociativeArray */
    protected $configTree;

    /** @var DispatcherInterface */
    protected $dispatcher;

    /** @var bool  */
    protected $disableDispatch = false;

    /** @var UiInterface[] */
    protected $uiHandlers = [];

    /**
     * ConfigManager constructor.
     *
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        $this->configTree = new AssociativeArray();
    }


    /**
     * Called when a config changed to dispatch event.
     *
     * @param ConfigInterface $config
     * @param mixed           $oldValue
     *
     * @throws UnhandledConfigurationException
     */
    public function valueChanged(ConfigInterface $config, $oldValue)
    {
        if (!isset($this->configurations[spl_object_hash($config)])) {
            throw new UnhandledConfigurationException("'{$config->getName()}' is not handled by the config manager!");
        }

        if ($this->disableDispatch) {
            return;
        }

        $this->saveConfigValue($config);

        $this->dispatcher->dispatch(
            'expansion.config_change',
            ['config' => $config, 'id' => $this->configurationIds[spl_object_hash($config)], 'oldValue' => $oldValue]
        );
    }

    /**
     * Register a config to be handled by the config manager.
     *
     * @param ConfigInterface $config
     * @param $id
     */
    public function registerConfig(ConfigInterface $config, $id)
    {
        $this->configurations[spl_object_hash($config)] = $config;
        $this->configurationIds[spl_object_hash($config)] = $id;
        $this->configTree->set($config->getPath(), $config);

        $this->loadConfigValue($config);
    }

    public function registerUi(UiInterface $ui)
    {
        $this->uiHandlers[] = $ui;
    }

    /**
     * Load config value from somewhere.
     *
     * @param ConfigInterface $config
     */
    protected function loadConfigValue(ConfigInterface $config)
    {
        $this->disableDispatch = true;
        // TODO load config from somewhere...
        $this->disableDispatch = false;
    }

    /**
     * Load config value from somewhere.
     *
     * @param ConfigInterface $config
     */
    protected function saveConfigValue(ConfigInterface $config)
    {
        // TODO save config somewhere...
    }

    /**
     * Get the config tree.
     *
     * @return AssociativeArray
     */
    public function getConfigTree(): AssociativeArray
    {
        return $this->configTree;
    }

    /**
     * Get proper handler to generate ui for a config element.
     *
     * @param ConfigInterface $config
     *
     * @return UiInterface|null
     */
    public function getUiHandler(ConfigInterface $config)
    {
        foreach ($this->uiHandlers as $ui) {
            if ($ui->isCompatible($config)) {
                return $ui;
            }
        }

        return null;
    }
}
