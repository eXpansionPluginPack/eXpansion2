<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Exceptions\Gui\MissingCloseActionException;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Controls\Quads\Quad_Bgs1;
use FML\Controls\Quads\Quad_Bgs1InRace;
use FML\Types\Container;

class Window extends Widget implements Container
{

    public function __construct(
        Group $group,
        ManiaScriptFactory $windowManiaScriptFactory,
        Translations $translationHelper,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($group, $translationHelper, $name, $sizeX, $sizeY, $posX, $posY);

        $this->translationHelper = $translationHelper;
        $windowFrame = $this->windowFrame;

        $titleHeight = 5.5;
        $closeButtonWidth = 9.5;
        $titlebarColor = "000e";
        $titleTextColor = "eff";

        // Frame to handle the content of the window.
        $this->contentFrame->setPosition(2, -$titleHeight - 2);
        $this->contentFrame->setSize($sizeX - 4, $sizeY - $titleHeight - 4);
        $windowFrame->addChild($this->contentFrame);

        // Title bar & title.
        $titleLabel = new Label();
        $titleLabel->setPosition(3, -$titleHeight / 3 - 1)
            ->setAlign(Label::LEFT, Label::CENTER2)
            ->setTextId($name)
            ->setTextColor($titleTextColor)
            ->setTextSize(2)
            ->setTranslate(true)
            ->setTextFont('RajdhaniMono')
            ->setId("TitleText");
        $windowFrame->addChild($titleLabel);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX, 0.33)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $windowFrame->addChild($titleBar);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX / 4, 0.5)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $windowFrame->addChild($titleBar);

        $titleBar = new Quad('Title');
        $titleBar->setSize($sizeX - $closeButtonWidth, $titleHeight)
            ->setBackgroundColor($titlebarColor)
            ->setScriptEvents(true);
        $windowFrame->addChild($titleBar);

        $this->closeButton = new Label('Close');
        $this->closeButton->setSize($closeButtonWidth, $titleHeight)
            ->setPosition($sizeX - $closeButtonWidth + ($closeButtonWidth / 2), -$titleHeight / 2)
            ->setAlign(Label::CENTER, Label::CENTER2)
            ->setText("✖")
            ->setTextColor('fff')
            ->setTextSize(2)
            ->setTextFont('OswaldMono')
            ->setScriptEvents(true)
            ->setAreaColor($titlebarColor)
            ->setAreaFocusColor('f22');
        $windowFrame->addChild($this->closeButton);

        //body
        $body = new Quad();
        $body->setSize($sizeX, $sizeY - $titleHeight)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor("222")
            ->setOpacity(0.8);
        $windowFrame->addChild($body);

        $body = new Quad();
        $body->setSize($sizeX, $sizeY - $titleHeight)
            ->setPosition(0, -$titleHeight)
            ->setStyles('Bgs1', 'BgDialogBlur')
            ->setId('WindowBg')
            ->setScriptEvents(true);
        $windowFrame->addChild($body);

        $body = new Quad();
        $body->setSize($sizeX + 10, $sizeY + 10)
            ->setPosition(-5, 5)
            ->setStyles('Bgs1InRace', 'BgButtonShadow');
        $windowFrame->addChild($body);

        // Add maniascript for window handling.
        $this->manialink->addChild($windowManiaScriptFactory->createScript(['']));
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
