<?php


namespace eXpansion\Core\Plugins\Gui;


use eXpansion\Core\DataProviders\Listener\UserGroupDataListenerInterface;
use eXpansion\Core\Model\Gui\ManialinkInerface;
use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Plugins\GuiHandler;

/**
 * Class ManialiveFactory
 *
 * @TODO handle update on single player.
 *
 * @package eXpansion\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class GroupManialinkFactory implements UserGroupDataListenerInterface
{
    /** @var  GuiHandler */
    protected $guiHandler;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $className;

    /** @var ManialinkInerface[]  */
    protected $manialinks = [];

    /** @var Group[] */
    protected $groups = [];

    /**
     * ManialiveFactory constructor.
     *
     * @param GuiHandler $guiHandler
     * @param string $className
     */
    public function __construct(GuiHandler $guiHandler, $name, $className)
    {
        $this->guiHandler = $guiHandler;
        $this->name = $name;
        $this->className = $className;
    }


    public function create(Group $group)
    {
        $this->manialinks[$group->getName()] = $this->createManialink($group);

        $this->guiHandler->addToDisplay($this->manialinks[$group->getName()]);
    }

    public function destroy(Group $group)
    {
        if (isset($this->manialinks[$group->getName()])) {
            $this->guiHandler->addToHide($this->manialinks[$group->getName()]);
        }
    }

    protected function createManialink(Group $group)
    {
        $className = $this->className;
        return new $className($group, $this->name);
    }

    public function onExpansionGroupDestroy(Group $group, $lastLogin)
    {
        if (isset($this->manialinks[$group->getName()])) {
            unset($this->manialinks[$group->getName()]);
        }
    }

    public function onExpansionGroupAddUser(Group $group, $loginAdded)
    {
    }

    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved)
    {
    }
}
