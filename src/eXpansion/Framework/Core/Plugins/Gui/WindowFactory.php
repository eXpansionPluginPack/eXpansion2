<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory;
use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use FML\Controls\Control;

/**
 * Class ManialiveFactory allow the creation of manialinks.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class WindowFactory extends WidgetFactory
{

    /** @var WindowFrameFactory */
    protected $windowFrameFactory;

    /**
     * WindowFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null $posX
     * @param null $posY
     * @param WindowFactoryContext $context
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        WindowFactoryContext $context
    ) {
        parent::__construct(
            $name,
            $sizeX,
            $sizeY,
            $posX,
            $posY,
            $context
        );

        $this->windowFrameFactory = $context->getWindowFrameFactory();
    }

    /**
     * @param Group $group
     *
     * @return Window
     */
    protected function createManialink(Group $group)
    {
        $className = $this->className;

        $manialink = new $className(
            $group,
            $this->translationsHelper,
            $this->windowFrameFactory,
            $this->name,
            $this->sizeX,
            $this->sizeY,
            $this->posX,
            $this->posY
        );

        $actionId = $this->actionFactory->createManialinkAction(
            $manialink,
            [$this, 'closeManialink'],
            []
        );

        $manialink->setCloseAction($actionId);

        return $manialink;
    }

    public function closeManialink(ManialinkInterface $manialink)
    {
        $this->destroy($manialink->getUserGroup());
    }
}
