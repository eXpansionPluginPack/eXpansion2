<?php


namespace eXpansion\Framework\Core\Plugins;


/**
 * Class StatusStoredPluginInterface
 *
 * @package eXpansion\Framework\Core\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
Interface StatusStoredPluginInterface extends StatusAwarePluginInterface
{
    /**
     * Get the current status of a plugin.
     *
     * @return boolean
     */
    public function getStatus();
}