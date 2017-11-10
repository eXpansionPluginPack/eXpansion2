<?php

namespace eXpansion\Bundle\Menu\Model\Menu;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Quad;

/**
 * Class AbstractItem
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
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

    /** @var Quad */
    private $icon;

    /** @var string|null */
    private $permission;

    /**
     * AbstractItem constructor.
     *
     * @param string $id
     * @param string $labelId
     * @param Quad $icon
     * @param null|string $permission
     */
    public function __construct($id, $path, $labelId, Quad $icon, $permission = null)
    {
        $this->id = $id;
        $this->path = $path;
        $this->labelId = $labelId;
        $this->icon = $icon;
        $this->permission = $permission;
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
     * Icon to display next to the label.
     *
     * @return Quad
     */
    public function getIcon()
    {
        return $this->icon;
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
     *
     *
     * @param Group $group
     * @param AdminGroups $adminGroups
     *
     * @return mixed
     */
    public function isVisibleFor(Group $group, AdminGroups $adminGroups)
    {
        if (is_null($this->permission)) {
            return true;
        }

        return $adminGroups->hasGroupPermission($group, $this->permission);
    }
}
