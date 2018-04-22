<?php

namespace eXpansion\Framework\Config\Model;

use eXpansion\Framework\Config\Services\ConfigManager;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;

/**
 * Class AbstractConfig
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Model
 */
abstract class AbstractConfig implements ConfigInterface
{
    /** @var string */
    protected $path;

    /** @var string */
    protected $name;

    /** @var string */
    protected $scope;

    /** @var string */
    protected $description;

    /** @var mixed */
    protected $defaultValue;

    /** @var ConfigManagerInterface */
    protected $configManager;

    /**
     * AbstractConfig constructor.
     *
     * @param string $path
     * @param string $name
     * @param string $scope
     * @param string $description
     * @param mixed $defaultValue
     * @param ConfigManagerInterface $configManager
     */
    public function __construct(
        string $path,
        string $scope,
        string $name,
        string $description,
        $defaultValue
    ) {
        $this->path = $path;
        $this->name = $name;
        $this->scope = $scope;
        $this->description = $description;
        $this->defaultValue = $defaultValue;
    }

    /**
     * @internal
     * @param ConfigManager $configManager
     */
    public function setConfigManager(ConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;
    }


    /**
     * @inheritdoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function getScope() : string
    {
        return $this->scope;
    }

    /**
     * @inheritdoc
     */
    public function isHidden(): bool
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @inheritdoc
     */
    public function getRawValue()
    {
        $value = $this->configManager->get($this->path);
        if (is_null($value)) {
            return $this->defaultValue;
        }

        return $value;
    }


    /**
     * @inheritdoc
     */
    public function get()
    {
        return $this->getRawValue();
    }

    /**
     * @inheritdoc
     */
    public function setRawValue($value)
    {
        $this->validate($value);

        return $this->configManager->set($this->path, $value);
    }

    /**
     * @inheritdoc
     */
    public function validate(&$value)
    {
        return;
    }
}
