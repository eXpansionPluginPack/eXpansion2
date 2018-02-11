<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;

/**
 * Class ManialiveFactory allow the creation of manialinks.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class WindowFactory extends FmlManialinkFactory
{

    /** @var WindowFrameFactory */
    protected $windowFrameFactory;

    /**
     * WindowFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
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

        $this->translationsHelper = $context->getTranslationsHelper();
        $this->uiFactory = $context->getUiFactory();
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
            $this,
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
            [],
            true
        );

        $manialink->setCloseAction($actionId);

        return $manialink;
    }

    public function closeManialink(ManialinkInterface $manialink)
    {
        $this->destroy($manialink->getUserGroup());
    }

    /**
     * @param ManialinkInterface|Window $manialink
     * @param bool|string               $busyStatus
     */
    public function setBusy(ManialinkInterface $manialink, $busyStatus = true)
    {
        $manialink->setBusy($busyStatus);
        $this->update($manialink->getUserGroup());
    }


}
