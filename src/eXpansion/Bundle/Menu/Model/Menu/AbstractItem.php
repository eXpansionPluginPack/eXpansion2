<?php

namespace eXpansion\Bundle\Menu\Model\Menu;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;

/**
 * Class AbstractItem
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\Menu\Model\Menu
 */
abstract class AbstractItem implements ItemInterface
{
    /** @var string */
    private $id;

    /** @var string */
    private $path = null;

    /** @var string */
    private $labelId;

    /** @var string|null */
    private $permission;

    /** @var AdminGroups */
    private $adminGroups;

    /**
     * AbstractItem constructor.
     *
     * @param             $id
     * @param             $path
     * @param             $labelId
     * @param AdminGroups $adminGroups
     * @param null        $permission
     */
    public function __construct($id, $path, $labelId, AdminGroups $adminGroups, $permission = null)
    {
        $this->id = $id;
        $this->path = $path;
        $this->labelId = $labelId;
        $this->permission = $permission;
        $this->adminGroups = $adminGroups;
    }

    /**
     * Unique identifier for menu item (needs to be unique in level)
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Label to be translated to be displayed on the menu.
     *
     * @return string
     */
    public function getLabelId()
    {
        return $this->labelId;
    }

    /**
     * Get the permission required to use this menu item.
     *
     * @return mixed
     */
    public function permission()
    {
        return $this->permission;
    }

    /**
     * @inheritdoc
     */
    public function isVisibleFor($login)
    {
        if (is_null($this->permission)) {
            return true;
        }

        return $this->adminGroups->hasPermission($login, $this->permission);
    }
}
