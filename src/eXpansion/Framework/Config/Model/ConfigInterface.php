<?php

namespace eXpansion\Framework\Config\Model;

use eXpansion\Framework\Config\Services\ConfigManager;

/**
 * Class ConfigInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Model
 */
interface ConfigInterface
{
    /**
     * Get path to the config. 'exemple : expansion/localrecors/race_nb'
     *
     * @return string
     */
    public function getPath() : string;

    /**
     * Get name of the configuration.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription() : string;

    /**
     * Get default raw value.
     *
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * Get raw value.
     *
     * @return mixed
     */
    public function getRawValue();

    /**
     * Set raw value.
     *
     * @param mixed $value
     *
     * @return void
     */
    public function setRawValue($value);
}
