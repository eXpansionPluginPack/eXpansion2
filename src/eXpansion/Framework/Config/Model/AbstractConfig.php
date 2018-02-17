<?php

namespace eXpansion\Framework\Config\Model;

use eXpansion\Framework\Config\Services\ConfigManager;

/**
 * Class AbstractConfig
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Model
 */
class AbstractConfig implements ConfigInterface
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

    /** @var ConfigManager */
    protected $configManager;

    /**
     * AbstractConfig constructor.
     *
     * @param string $path
     * @param string $name
     * @param string $scope
     * @param string $description
     * @param mixed $defaultValue
     * @param ConfigManager $configManager
     */
    public function __construct(
        string $path,
        string $scope,
        string $name,
        string $description,
        $defaultValue,
        ConfigManager $configManager
    ) {
        $this->path = $path;
        $this->name = $name;
        $this->scope = $scope;
        $this->description = $description;
        $this->defaultValue = $defaultValue;
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
        return $this->configManager->get($this->path);
    }

    /**
     * @inheritdoc
     */
    public function setRawValue($value)
    {
        return $this->configManager->set($this->path, $value);
    }
}
