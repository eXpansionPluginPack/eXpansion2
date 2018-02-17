<?php

namespace eXpansion\Framework\Config\Services;

use eXpansion\Framework\Config\Exception\UnhandledConfigurationException;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Ui\UiInterface;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;

use eXpansion\Framework\Core\Storage\GameDataStorage;
use League\Flysystem\File;
use League\Flysystem\Filesystem;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use Psr\Log\LoggerInterface;

/**
 * Class ConfigManager
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Services
 */
class ConfigManager implements ConfigManagerInterface
{
    /** @var DispatcherInterface */
    protected $dispatcher;

    /** @var GameDataStorage */
    protected $gameDataStorage;

    /** @var Filesystem */
    protected $filesystem;

    /** @var LoggerInterface */
    protected $logger;

    /** @var ConfigInterface */
    protected $configurationDefinitions = [];

    /** @var string[] */
    protected $configurationIds = [];

    /** @var AssociativeArray */
    protected $configTree;

    /** @var AssociativeArray */
    protected $globalConfigurations;

    /** @var AssociativeArray */
    protected $keyConfigurations;

    /** @var AssociativeArray */
    protected $serverConfigurations;

    /** @var bool  */
    protected $disableDispatch = false;

    /**
     * ConfigManager constructor.
     *
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(
        DispatcherInterface $dispatcher,
        GameDataStorage $gameDataStorage,
        Filesystem $filesystem,
        LoggerInterface $logger
    ) {
        $this->dispatcher = $dispatcher;
        $this->gameDataStorage = $gameDataStorage;
        $this->filesystem = $filesystem;
        $this->logger = $logger;

        $this->configTree = new AssociativeArray();
    }

    /**
     * @inheritdoc
     */
    public function set($path, $value) : boolean
    {
        /** @var ConfigInterface $configDefinition */
        $configDefinition = $this->configTree->get($path);
        if (is_null($configDefinition)) {
            throw new UnhandledConfigurationException("'{$path}' is not handled by the config manager!");
        }

        // Fetch old value for event.
        $oldValue = $this->get($path);

        // Put new value.
        $configs = $this->getAllConfigs($configDefinition->getScope());
        $configs->set($path, $value);

        // Dispatch and save changes.
        if ($this->disableDispatch || $oldValue === $value) {
            $this->logger->debug(
                'New conig was set, but no changes, save and dispatch are canceled!',
                ['path' => $path]
            );
            return true;
        }

        $this->saveConfigValues();
        $this->dispatcher->dispatch(
            'expansion.config.change',
            [
                'config' => $configDefinition,
                'id' => $this->configurationIds[spl_object_hash($configDefinition)],
                'oldValue' => $oldValue
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function get($path)
    {
        /** @var ConfigInterface $configDefinition */
        $configDefinition = $this->configTree->get($path);
        if (is_null($configDefinition)) {
            throw new UnhandledConfigurationException("'{$path}' is not handled by the config manager!");
        }

        $configs = $this->getAllConfigs($configDefinition->getScope());
        $value = $configs->get($path);

        if (is_null($value)) {
            return $configDefinition->getDefaultValue();
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getAllConfigs($scope) : AssociativeArray
    {
        $this->loadConfigValues();

        switch ($scope) {
            case ConfigInterface::SCOPE_SERVER:
                return $this->serverConfigurations;
            case ConfigInterface::SCOPE_KEY:
                return $this->keyConfigurations;
            case ConfigInterface::SCOPE_GLOBAL:
            default:
                return $this->globalConfigurations;
        }
    }

    /**
     * Register a config to be handled by the config manager.
     *
     * @param ConfigInterface $config
     * @param $id
     */
    public function registerConfig(ConfigInterface $config, $id)
    {
        $this->configurationDefinitions[spl_object_hash($config)] = $config;
        $this->configurationIds[spl_object_hash($config)] = $id;
        $this->configTree->set($config->getPath(), $config);
    }

    /**
     * @inheritdoc
     */
    public function loadConfigValues()
    {
        if (!is_null($this->globalConfigurations)) {
            return;
        }

        $this->globalConfigurations = new AssociativeArray();
        $this->keyConfigurations = new AssociativeArray();
        $this->serverConfigurations = new AssociativeArray();

        /** @var AssociativeArray[] $configs */
        $configs = [
            'global' => $this->globalConfigurations,
            'key' => $this->keyConfigurations,
            'server-' . $this->gameDataStorage->getSystemInfo()->serverLogin => $this->serverConfigurations,
        ];

        foreach ($configs as $filekey => $config) {
            $this->logger->debug(
                'Loading config file',
                ['file' => "config-$filekey.json"]
            );

            /** @var File $file */
            $file = $this->filesystem->get("config-$filekey.json");

            if ($file->exists()) {
                $values = json_decode($file->read(), true);
                $config->setData($values);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function saveConfigValues()
    {
        /** @var AssociativeArray[] $configs */
        $configs = [
            'global' => $this->globalConfigurations,
            'key' => $this->keyConfigurations,
            'server-' . $this->gameDataStorage->getSystemInfo()->serverLogin => $this->serverConfigurations,
        ];

        foreach ($configs as $filekey => $config) {
            $this->logger->debug(
                'Saving config file',
                ['file' => "config-$filekey.json"]
            );

            $encoded = json_encode($config->getArray(), JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            $this->filesystem->put("config-$filekey.json", $encoded);
        }
    }

    /**
     * @inheritdoc
     */
    public function getConfigDefinitionTree(): AssociativeArray
    {
        return $this->configTree;
    }
}
