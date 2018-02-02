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
    const SCOPE_GLOBAL = 'global';
    const SCOPE_KEY = 'key';
    const SCOPE_SERVER = 'server';

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
     * Get the scope of the variable.
     *
     * @return string
     */
    public function getScope() : string;

    /**
     * Do we display the config in the config windows.
     *
     * @return bool
     */
    public function isHidden(): boolean;

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
     * @return boolean true if sucessfuly changed and false if not.
     */
    public function setRawValue($value);
}
