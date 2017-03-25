<?php


namespace eXpansion\Core\Plugins\Gui;


use eXpansion\Core\DataProviders\Listener\UserGroupDataListenerInterface;
use eXpansion\Core\Model\Gui\Manialink;
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
class ManialinkFactory implements UserGroupDataListenerInterface
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
        $this->name = $name;
        $this->className = $className;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->posX = $posX;
        $this->posY = $posY;
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
        return new $className($group, $this->name, $this->sizeX, $this->sizeY, $this->posX, $this->posY);
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
