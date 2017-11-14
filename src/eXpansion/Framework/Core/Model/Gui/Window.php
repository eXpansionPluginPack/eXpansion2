<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Exceptions\Gui\MissingCloseActionException;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Control;
use FML\Types\Container;

class Window extends Widget implements Container
{
    /** @var WindowFrameFactoryInterface */
    private $windowFrameFactory;

    public function __construct(
        Group $group,
        Translations $translationHelper,
        WindowFrameFactory $windowFrameFactory,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($group, $translationHelper, $name, $sizeX, $sizeY, $posX, $posY);

        $this->translationHelper = $translationHelper;

        $windowFrameFactory->setManialinkInterface($this);
        $windowFrameFactory->build($this->manialink, $this->windowFrame, $name, $sizeX, $sizeY);
        $this->windowFrameFactory = $windowFrameFactory;
    }

    public function setCloseAction($action)
    {
        $this->windowFrameFactory->setCloseAction($action);
    }
}
