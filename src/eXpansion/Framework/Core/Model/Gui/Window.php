<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactoryInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;

/**
 * Class Window is a specific type of FmlManialink.
 *
 * @package eXpansion\Framework\Core\Model\Gui
 */
class Window extends FmlManialink
{
    /** @var Control */
    protected $closeButton;
    public $isBusy = false;

    protected $busyFrame;
    public $busyCounter = 0;

    /**
     * Window constructor is
     *
     * @param ManialinkFactoryInterface   $manialinkFactory
     * @param Group                       $group
     * @param Translations                $translationHelper
     * @param WindowFrameFactoryInterface $windowFrameFactory
     * @param int                         $name
     * @param float|null                  $sizeX
     * @param null                        $sizeY
     * @param null                        $posX
     * @param null                        $posY
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
        $this->busyFrame = Frame::create();
        $this->busyFrame->setSize($sizeX, $sizeY)->setZ(1000);
        $this->addChild($this->busyFrame);
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

    /**
     * @param bool $bool sets busy status of the window
     */
    public function setBusy($bool = true)
    {
        $this->busyCounter = 0;
        $this->isBusy = (bool)$bool;
        if ($bool) {
            $text = "Please wait...";
            if (is_string($bool)) {
                $text = (string)$bool;
            }
            $lbl = Label::create();
            $lbl->setText($text)->setTextColor("fff")->setTextSize(6)
                ->setSize(90, 12)->setAlign("center", "center")
                ->setPosition($this->busyFrame->getWidth() / 2, -($this->busyFrame->getHeight() / 2));

            $this->busyFrame->addChild($lbl);
            $quad = Quad::create();
            $quad->setStyles("Bgs1", "BgDialogBlur");
            $quad->setBackgroundColor("f00")->setSize($this->busyFrame->getWidth(), $this->busyFrame->getHeight());
            $this->busyFrame->addChild($quad);
        } else {
            $this->busyFrame->removeAllChildren();
        }
    }

}
