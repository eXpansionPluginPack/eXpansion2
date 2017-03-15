<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 11:53
 */

namespace eXpansion\Core\DataProviders;

/**
 * AbstractDataProvider for all data providers to use to simplify registration & dispatch.
 *
 * @package eXpansion\Core\DataProviders
 */
abstract class AbstractDataProvider
{
    protected $plugins = [];

    /**
     * Register a plugin to be handled by ths provider
     *
     * @param string $pluginId The service name of the plugin.
     *
     * @param Object $pluginService The plugin object.
     */
    public function registerPlugin($pluginId, $pluginService)
    {
        $this->plugins[$pluginId] = $pluginService;
    }

    /**
     * Remove a plugin so that it won't be handled anymore.
     *
     * @param $pluginId
     *
     */
    public function deletePlugin($pluginId)
    {
        if (isset($this->plugins[$pluginId])) {
            unset($this->plugins[$pluginId]);
        }
    }

    /**
     * Dispatch method call to all plugins.
     *
     * @param string $method method to call.
     * @param array $params
     */
    protected function dispatch($method, $params)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->$method(...$params);
        }
    }
}