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

    abstract public function getCompatibleInterface();

    public function registerPlugin($pluginId, $pluginService)
    {
        // @TODO move this logic to tags.
        $interface = $this->getCompatibleInterface();
        if (!$pluginService instanceof $interface) {
            // @TODO create custom exceptions.
            throw new \Exception("Un compatible plugin tries to use data provider");
        }

        $this->plugins[$pluginId] = $pluginService;
    }

    public function dispatch($method, $params)
    {
        foreach ($this->plugins as $plugin) {
            $plugin->$method(...$params);
        }
    }
}