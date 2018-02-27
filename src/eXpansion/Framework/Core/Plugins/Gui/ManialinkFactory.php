<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryContext;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;

/**
 * Class ManialiveFactory allow the creation of manialinks.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class ManialinkFactory implements ManialinkFactoryInterface
{
    /** @var  GuiHandler */
    protected $guiHandler;

    /** @var Factory */
    protected $groupFactory;

    /** @var ActionFactory */
    protected $actionFactory;

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $className;

    /** @var Group[] */
    protected $groups = [];

    /** @var float|int */
    protected $sizeX;

    /** @var float|int */
    protected $sizeY;

    /** @var float|int */
    protected $posX;

    /** @var float|int */
    protected $posY;

    /**
     * ManialinkFactory constructor.
     *
     * @param                         $name
     * @param                         $sizeX
     * @param                         $sizeY
     * @param null                    $posX
     * @param null                    $posY
     * @param ManialinkFactoryContext $context
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        ManialinkFactoryContext $context
    ) {
        if (is_null($posX)) {
            $posX = $sizeX / -2;
        }

        if (is_null($posY)) {
            $posY = $sizeY / 2;
        }

        $this->guiHandler = $context->getGuiHandler();
        $this->groupFactory = $context->getGroupFactory();
        $this->actionFactory = $context->getActionFactory();
        $this->className = $context->getClassName();
        $this->name = $name;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->posX = $posX;
        $this->posY = $posY;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return spl_object_hash($this);
    }

    /**
     * Creates a new manialink.
     *
     * @param string|array|Group $group
     *
     * @return Group
     */
    public function create($group)
    {
        if (is_string($group)) {
            $group = $this->groupFactory->createForPlayer($group);
        } else {
            if (is_array($group)) {
                $group = $this->groupFactory->createForPlayers($group);
            }
        }

        if (!is_null($this->guiHandler->getManialink($group, $this))) {
            $this->update($group);

            return $group;
        }


        $ml = $this->createManialink($group);
        $this->guiHandler->addToDisplay($ml, $this);

        $this->createContent($ml);
        $this->updateContent($ml);

        return $group;
    }

    /**
     * Request an update for manialink.
     *
     * @param string|array|Group $group
     */
    public function update($group)
    {
        if (is_string($group)) {
            $group = $this->groupFactory->createForPlayer($group);
        } else {
            if (is_array($group)) {
                $group = $this->groupFactory->createForPlayers($group);
            }
        }

        $ml = $this->guiHandler->getManialink($group, $this);
        if ($ml) {
            $this->actionFactory->destroyNotPermanentActions($ml);
            if ($ml instanceof Window) {
                $ml->busyCounter += 1;

                if ($ml->isBusy && $ml->busyCounter > 1) {
                    $ml->setBusy(false);
                }
            }
            $this->updateContent($ml);
            $this->guiHandler->addToDisplay($ml, $this);
        }
    }

    /**
     * Create content in the manialink.
     *
     * @param ManialinkInterface $manialink
     *
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        // Put content in the manialink here.
    }

    /**
     * Update content in the manialink.
     *
     * @param ManialinkInterface $manialink
     *
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        // Put content in the manialink here.
    }

    /**
     *  Hides and frees manialink resources
     *
     * @param Group $group
     *
     */
    public function destroy(Group $group)
    {
        $ml = $this->guiHandler->getManialink($group, $this);
        if ($ml) {
            $this->guiHandler->addToHide($ml, $this);
        }
    }

    /**
     * Create manialink object for user group.
     *
     * @param Group $group
     *
     * @return Manialink
     */
    protected function createManialink(Group $group)
    {
        $className = $this->className;

        return new $className($this, $group, $this->name, $this->sizeX, $this->sizeY, $this->posX, $this->posY);
    }
}
