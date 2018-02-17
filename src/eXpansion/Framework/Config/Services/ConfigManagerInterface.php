<?php

namespace eXpansion\Framework\Config\Services;

use eXpansion\Framework\Config\Exception\UnhandledConfigurationException;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class ConfigManagerInterface
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Framework\Config\Services
 */
interface ConfigManagerInterface
{
    /**
     * Set the raw value of a configuration.
     *
     * @param string $path The configuration path to set into
     * @param mixed $value
     *
     * @return bool
     * @throws UnhandledConfigurationException If the path is not valid, and such config doesen't exist.
     */
    public function set($path, $value): bool;


    /**
     * Get the raw value of a configuration variable.
     *
     * @param string $path The configuration path to get from.
     *
     * @return mixed
     * @throws UnhandledConfigurationException If the path is not valid, and such config doesen't exist.
     */
    public function get($path);

    /**
     * Get all config values of a certain scope.
     *
     * @param $scope
     *
     * @return AssociativeArray
     */
    public function getAllConfigs($scope) : AssociativeArray;

    /**
     * Loads all config value from the json files.
     */
    public function loadConfigValues();

    /**
     * Saves all config values in json files.
     */
    public function saveConfigValues();

    /**
     * Get the config tree.
     *
     * @return AssociativeArray
     */
    public function getConfigDefinitionTree(): AssociativeArray;
}