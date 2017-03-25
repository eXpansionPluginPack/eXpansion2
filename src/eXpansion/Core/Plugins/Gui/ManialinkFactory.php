<?php

namespace eXpansion\Core\Plugins\Gui;

use eXpansion\Core\Model\Gui\Manialink;
use eXpansion\Core\Model\Gui\ManialinkInerface;
use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Plugins\GuiHandler;
use eXpansion\Core\Plugins\UserGroups\Factory;

/**
 * Class ManialiveFactory allow the creation of manialinks.
 *
 * @package eXpansion\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class ManialinkFactory
{
    /** @var  GuiHandler */
    protected $guiHandler;

    /** @var Factory  */
    protected $groupFactory;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $className;

    /** @var ManialinkInerface[]  */
    protected $manialinks = [];

    /** @var Group[] */
    protected $groups = [];

    /** @var float */
    protected $sizeX;

    /** @var float */
    protected $sizeY;

    /** @var float */
    protected $posX;

    /** @var float */
    protected $posY;

    /**
     * GroupManialinkFactory constructor.
     *
     * @param GuiHandler $guiHandler
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param string $className
     */
    public function __construct(
        GuiHandler $guiHandler,
        Factory $groupFactory,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        $className = Manialink::class
    ) {
        if (is_null($posX)) {
            $posX = $sizeX/-2;
        }

        if (is_null($posY)) {
            $posY = $sizeY/2;
        }

        $this->guiHandler = $guiHandler;
        $this->groupFactory = $groupFactory;
        $this->name = $name;
        $this->className = $className;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->posX = $posX;
        $this->posY = $posY;
    }

    /**
     * Create & display manialink for user.
     *
     * @param Group|string|string[] $group
     *
     * @return Group
     */
    public function create($group)
    {
        if (is_string($group)) {
            $group = $this->groupFactory->createForPlayer($group);
        } else if (is_array($group)) {
            $group = $this->groupFactory->createForPlayers($group);
        }

        $this->manialinks[$group->getName()] = $this->createManialink($group);
        $this->guiHandler->addToDisplay($this->manialinks[$group->getName()]);

        return $group;
    }

    /**
     * Hide & destoy manialink fr user.
     *
     * @param Group $group
     *
     * @return void
     */
    public function destroy(Group $group)
    {
        if (isset($this->manialinks[$group->getName()])) {
            $this->guiHandler->addToHide($this->manialinks[$group->getName()]);
            unset($this->manialinks[$group->getName()]);
        }
    }

    /**
     * Create manialink object for user group.
     *
     * @param Group $group
     *
     * @return mixed
     */
    protected function createManialink(Group $group)
    {
        $className = $this->className;
        return new $className($group, $this->name, $this->sizeX, $this->sizeY, $this->posX, $this->posY);
    }

    /**
     * When a gup is destroyed, destroy manialinks of the group.
     *
     * @param Group $group
     * @param $lastLogin
     *
     * @return void
     */
    public function onExpansionGroupDestroy(Group $group, $lastLogin)
    {
        if (isset($this->manialinks[$group->getName()])) {
            // Gui Handler will handle delete by it's own.
            unset($this->manialinks[$group->getName()]);
        }
    }

    public function onExpansionGroupAddUser(Group $group, $loginAdded)
    {
        // nothing to do here.
    }

    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved)
    {
        // nothing to do here.
    }
}
