<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Exceptions\Gui\MissingCloseActionException;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactoryInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Control;
use FML\Types\Container;

/**
 * Class Window is a specific type of FmlManialink.
 *
 * @package eXpansion\Framework\Core\Model\Gui
 */
class Window extends FmlManialink
{
    /** @var Control  */
    protected $closeButton;

    /**
     * Window constructor is
     *
     * @param ManialinkFactoryInterface $manialinkFactory
     * @param Group $group
     * @param Translations $translationHelper
     * @param WindowFrameFactoryInterface $windowFrameFactory
     * @param int $name
     * @param float|null $sizeX
     * @param null $sizeY
     * @param null $posX
     * @param null $posY
     */
    public function __construct(
        ManialinkFactoryInterface $manialinkFactory,
        Group $group,
        Translations $translationHelper,
        WindowFrameFactoryInterface $windowFrameFactory,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($manialinkFactory, $group, $translationHelper, $name, $sizeX, $sizeY, $posX, $posY);

        $this->translationHelper = $translationHelper;
        $this->closeButton = $windowFrameFactory->build($this, $this->windowFrame, $name, $sizeX, $sizeY);
    }

    /**
     * Set action to close the window.
     *
     * @param $actionId
     */
    public function setCloseAction($actionId)
    {
        $this->closeButton->addDataAttribute('action', $actionId);
    }
}
