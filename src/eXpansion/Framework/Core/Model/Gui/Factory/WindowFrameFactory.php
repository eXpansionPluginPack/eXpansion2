<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Window;
use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\ManiaLink;
use FML\Types\Container;

/**
 * Class WindowFrameFactory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Core\Model\Gui\Factory
 */
class WindowFrameFactory implements WindowFrameFactoryInterface
{
    /** @var ManiaScriptFactory */
    protected $windowManiaScriptFactory;

    /**
     * WindowFrameFactory constructor.
     *
     * @param ManiaScriptFactory $maniaScriptFactory
     */
    public function __construct(ManiaScriptFactory $maniaScriptFactory)
    {
        $this->windowManiaScriptFactory = $maniaScriptFactory;
    }

    /**
     * Build the window frame content.
     *
     * @param Window $manialink
     * @param Frame|Container $mainFrame to build into
     * @param $name
     * @param float $sizeX Size of the inner frame to build the window frame around
     * @param float $sizeY Size of the inner frame to build the window frame around
     *
     * @return Control
     */
    public function build(Window $manialink, Frame $mainFrame, $name, $sizeX, $sizeY)
    {
        $titleHeight = 5.5;
        $closeButtonWidth = 9.5;
        $titlebarColor = "000e";
        $titleTextColor = "eff";

        // Creating sub frame to keep all the pieces together.
        $frame = new Frame();
        $frame->setPosition(-2, ($titleHeight) + 2);
        $mainFrame->addChild($frame);

        // Size of the actual window.
        $sizeX += 4;
        $sizeY += $titleHeight + 2;

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
        $frame->addChild($titleLabel);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX, 0.33)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $frame->addChild($titleBar);

        $titleBar = new Quad();
        $titleBar->setSize($sizeX / 4, 0.5)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor('fff');
        $frame->addChild($titleBar);

        $titleBar = new Quad('Title');
        $titleBar->setSize($sizeX - $closeButtonWidth, $titleHeight)
            ->setBackgroundColor($titlebarColor)
            ->setScriptEvents(true);
        $frame->addChild($titleBar);

        $closeButton = new Label('Close');
        $closeButton->setSize($closeButtonWidth, $titleHeight)
            ->setPosition($sizeX - $closeButtonWidth + ($closeButtonWidth / 2), -$titleHeight / 2)
            ->setAlign(Label::CENTER, Label::CENTER2)
            ->setText("✖")
            ->setTextColor('fff')
            ->setTextSize(2)
            ->setTextFont('OswaldMono')
            ->setScriptEvents(true)
            ->setAreaColor($titlebarColor)
            ->setAreaFocusColor('f22');
        $frame->addChild($closeButton);


        //body
        $body = new Quad();
        $body->setSize($sizeX, $sizeY - $titleHeight)
            ->setPosition(0, -$titleHeight)
            ->setBackgroundColor("222")
            ->setOpacity(0.8);
        $frame->addChild($body);

        $body = new Quad();
        $body->setSize($sizeX, $sizeY - $titleHeight)
            ->setPosition(0, -$titleHeight)
            ->setStyles('Bgs1', 'BgDialogBlur')
            ->setId('WindowBg')
            ->setScriptEvents(true);
        $frame->addChild($body);

        $body = new Quad();
        $body->setSize($sizeX + 10, $sizeY + 10)
            ->setPosition(-5, 5)
            ->setStyles('Bgs1InRace', 'BgButtonShadow');
        $frame->addChild($body);



        // Add maniascript for window handling.
        $manialink->getFmlManialink()->addChild($this->windowManiaScriptFactory->createScript(['']));
        $manialink->getFmlManialink()->getScript(true)->addCustomScriptLabel("exp_dummy", <<<EOL
        // this dummy is here to force FML script generation on all windowFactory classes.
        if (False) { 
            return; 
        }
EOL
        );

        return $closeButton;
    }
}
