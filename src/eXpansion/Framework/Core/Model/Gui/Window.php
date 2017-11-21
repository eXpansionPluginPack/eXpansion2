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
    /** @var Control  */
    protected $closeButton;

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
        $this->closeButton = $windowFrameFactory->build($this->manialink, $this->windowFrame, $name, $sizeX, $sizeY);
    }

    /**
     * Set action to close the window.
     *
     * @param $actionId
     */
    public function setCloseAction($actionId)
    {
        $this->closeButton->setDataAttributes(['action' => $actionId]);
    }

    public function getXml()
    {
        if (empty($this->closeButton->getDataAttribute('action'))) {
            throw new MissingCloseActionException("Close action is missing for window. Check if you are using the proper factory.");
        }

        return parent::getXml();
    }
}
