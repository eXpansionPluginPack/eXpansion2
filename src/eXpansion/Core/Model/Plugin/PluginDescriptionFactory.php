<?php

namespace eXpansion\Core\Model\Plugin;

/**
 * Class PluginDescriptionFactory crates plugin description instances.
 *
 * @package eXpansion\Core\Model\Plugin
 * @author Oliver de Cramer
 */
class PluginDescriptionFactory
{
    protected $className;

    /**
     * PluginFactory constructor.
     *
     * @param $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * Create an instance for a certain plugin.
     *
     * @param $pluginId
     *
     * @return PluginDescription
     */
    public function create($pluginId)
    {
        $class = $this->className;

        return new $class($pluginId);
    }
}
