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
    protected $description;

    /** @var mixed */
    protected $defaultValue;

    /** @var mixed */
    protected $rawValue;

    /** @var ConfigManager */
    protected $configManager;

    /**
     * AbstractConfig constructor.
     *
     * @param string $path
     * @param string $name
     * @param string $description
     * @param mixed $defaultValue
     * @param ConfigManager $configManager
     */
    public function __construct(string $path, string $name, string $description, mixed $defaultValue, ConfigManager $configManager)
    {
        $this->path = $path;
        $this->name = $name;
        $this->description = $description;
        $this->defaultValue = $defaultValue;
        $this->configManager = $configManager;
    }


    /**
     * Get path to the config. 'exemple : expansion/localrecors'
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get name of the configuration.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get default raw value.
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Get raw value.
     *
     * @return mixed
     */
    public function getRawValue()
    {
        return $this->rawValue;
    }

    /**
     * Set raw value.
     *
     * @param mixed $value
     *
     * @throws \eXpansion\Framework\Config\Exception\UnhandledConfigurationException
     */
    public function setRawValue($value)
    {
        if ($value == $this->rawValue) {
            // no change ignore set.
            return;
        }

        $oldValue = $this->rawValue;
        $this->rawValue = $value;

        $this->configManager->valueChanged($this, $oldValue);
    }
}
