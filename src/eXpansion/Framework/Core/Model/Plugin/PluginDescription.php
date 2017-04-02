<?php

namespace eXpansion\Framework\Core\Model\Plugin;

/**
 * Store all the metadata for a certain plugin.
 *
 * @package eXpansion\Framework\Core\Model\Plugin
 * @author Oliver de Cramer
 */
class PluginDescription
{
    /** @var string */
    protected $pluginId;

    /** @var string[] */
    protected $dataProviders = [];

    /** @var string[] */
    protected $parents = [];

    /** @var PluginDescription[] */
    protected $childrens = [];

    /** @var bool  */
    protected $isEnabled = false;

    /**
     * PluginDescription constructor.
     * @param string $pluginId
     */
    public function __construct($pluginId)
    {
        $this->pluginId = $pluginId;
    }

    /**
     * @return string
     */
    public function getPluginId()
    {
        return $this->pluginId;
    }

    /**
     * @return string[]
     */
    public function getDataProviders()
    {
        return $this->dataProviders;
    }

    /**
     * @return string[]
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @param string[] $parents
     */
    public function setParents($parents)
    {
        $this->parents = $parents;
    }

    /**
     * @param string[] $dataProviders
     */
    public function setDataProviders($dataProviders)
    {
        $this->dataProviders = $dataProviders;
    }

    /**
     * @return PluginDescription[]
     */
    public function getChildrens()
    {
        return $this->childrens;
    }

    /**
     * @param PluginDescription $parent
     */
    public function addChildren(PluginDescription $parent)
    {
        $this->childrens[] = $parent;
    }

    /**
     * @return boolean
     */
    public function isIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param boolean $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }
}
