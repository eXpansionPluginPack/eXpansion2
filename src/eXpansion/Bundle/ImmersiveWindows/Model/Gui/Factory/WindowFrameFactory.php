<?php

namespace eXpansion\Bundle\ImmersiveWindows\Model\Gui\Factory;

use eXpansion\Bundle\Menu\DataProviders\MenuItemProvider;
use eXpansion\Bundle\Menu\Gui\MenuTabsFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory as OriginalWindowFrameFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\ManiaLink;

/**
 * Class WindowFrameFactory
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\ImmersiveWindows\Model\Gui\Factory
 */
class WindowFrameFactory extends OriginalWindowFrameFactory
{
    /** @var MenuTabsFactory */
    protected $menuTabsFactory;

    /** @var MenuItemProvider */
    protected $menuItemProvider;
    /**
     * @var ManiaScriptFactory
     */
    protected $maniaScriptFactory;
    /**
     * @var ManialinkInterface
     */
    protected $manialinkInterface;

    /**
     * WindowFrameFactory constructor.
     *
     * @param ManiaScriptFactory $maniaScriptFactory
     * @param MenuTabsFactory $menuTabsFactory
     * @param MenuItemProvider $menuItemProvider
     * @param ManialinkInterface $manialink
     */
    public function __construct(
        ManiaScriptFactory $maniaScriptFactory,
        MenuTabsFactory $menuTabsFactory,
        MenuItemProvider $menuItemProvider
    ) {
        parent::__construct($maniaScriptFactory);
        $this->maniaScriptFactory = $maniaScriptFactory;
        $this->menuTabsFactory = $menuTabsFactory;
        $this->menuItemProvider = $menuItemProvider;

    }

    /**
     * @inheritdoc
     */
    public function build(
        ManiaLink $manialink,
        Frame $mainFrame,
        $name,
        $sizeX,
        $sizeY
    ) {
        // Creating sub frame to keep all the pieces together. Position needs to be top left corner.
        $frame = new Frame();
        $frame->setPosition(-144 - $mainFrame->getX(), 82 - $mainFrame->getY());
        $mainFrame->addChild($frame);

        // Creating the tabs.
        $mainFrame->addChild(
            $this->menuTabsFactory->createTabsMenu(
                $this->manialinkInterface,
                $frame,
                $this->menuItemProvider->getRootItem(),
                'admin/admin'
            )
        );

        $closeButton = new Label('Close');
        $closeButton->setSize(6, 6)
            ->setPosition(132, 72)
            ->setAlign(Label::CENTER, Label::CENTER2)
            ->setText("✖")
            ->setTextColor('fff')
            ->setTextSize(2)
            ->setTextFont('OswaldMono')
            ->setScriptEvents(true)
            ->setAreaColor("FFF")
            ->setAreaFocusColor('f22');
        $frame->addChild($closeButton);

        /*
         * Adding background frame
         */
        $bgFrame = Frame::create("background");

        $quad = new Quad();
        $quad->addClass("bg")
            ->setId("mainBg")
            ->setPosition(0, 0)
            ->setSize(322, 182);
        $quad->setAlign("center", "center")
            ->setStyles("Bgs1", "BgDialogBlur");
        $bgFrame->addChild($quad);

        $frame->addChild($bgFrame);

        return $closeButton;
    }

    /**
     * @param ManialinkInterface $manialinkInterface
     */
    public function setManialinkInterface(ManialinkInterface $manialinkInterface)
    {
        $this->manialinkInterface = $manialinkInterface;
    }


}
