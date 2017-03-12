<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 11:53
 */

namespace eXpansion\Core\DataProviders;


abstract class AbstractDataProvider
{
    protected $plugins = [];

    public function registerPlugin($pluginId, $pluginService)
    {
        $this->plugins[$pluginId] = $pluginService;
    }

    public function dispatch($method, $params)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->$method(...$params);
        }
    }
}