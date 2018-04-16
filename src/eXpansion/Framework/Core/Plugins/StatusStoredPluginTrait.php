<?php


namespace eXpansion\Framework\Core\Plugins;


/**
 * Class StatusStoredPluginTrait
 *
 * @package eXpansion\Framework\Core\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
Trait StatusStoredPluginTrait
{

    protected $currentStatus = false;

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        $this->currentStatus = $status;
    }

    /**
     * Get the current status of a plugin.
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->currentStatus;
    }


}