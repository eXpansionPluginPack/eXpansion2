<?php

namespace eXpansion\Bundle\ImmersiveWindows\Model\Gui\Factory;

use eXpansion\Bundle\ImmersiveWindows\Plugins\WindowsGuiHandler;
use eXpansion\Bundle\Menu\DataProviders\MenuItemProvider;
use eXpansion\Bundle\Menu\Gui\MenuTabsFactory;
use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use eXpansion\Bundle\Menu\Plugins\Gui\MenuContentFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\WindowFrameFactory as OriginalWindowFrameFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\WindowFrameFactoryInterface;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Ui\Factory;
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
class WindowFrameFactory extends OriginalWindowFrameFactory implements WindowFrameFactoryInterface
{
    /** @var MenuTabsFactory */
    protected $menuTabsFactory;

    /** @var MenuItemProvider */
    protected $menuItemProvider;

    /** @var MenuContentFactory */
    protected $menuContentFactory;

    /** @var WindowsGuiHandler */
    protected $windowsGuiHandler;

    /**
     * @var ManiaScriptFactory
     */
    protected $maniaScriptFactory;
    /**
     * @var ManialinkInterface
     */
    protected $manialinkInterface;
    /**
     * @var Factory
     */
    protected $uiFactory;

    /**
     * WindowFrameFactory constructor.
     *
     * @param ManiaScriptFactory $maniaScriptFactory
     * @param MenuTabsFactory $menuTabsFactory
     * @param MenuItemProvider $menuItemProvider
     * @param Factory $uiFactory
     */
    public function __construct(
        ManiaScriptFactory $maniaScriptFactory,
        MenuTabsFactory $menuTabsFactory,
        MenuItemProvider $menuItemProvider,
        Factory $uiFactory,
        MenuContentFactory $menuContentFactory,
        WindowsGuiHandler $windowsGuiHandler
    ) {
        parent::__construct($maniaScriptFactory);
        $this->maniaScriptFactory = $maniaScriptFactory;
        $this->menuTabsFactory = $menuTabsFactory;
        $this->menuItemProvider = $menuItemProvider;
        $this->uiFactory = $uiFactory;
        $this->menuContentFactory = $menuContentFactory;
        $this->windowsGuiHandler = $windowsGuiHandler;
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
        $frame->setPosition(-160 - $mainFrame->getX(), 90 - $mainFrame->getY());

        $tabsFrame = new Frame();
        $tabsFrame->setPosition(-144 - $mainFrame->getX(), 82 - $mainFrame->getY());

        // Creating the tabs.
        $mainFrame->addChild(
            $this->menuTabsFactory->createTabsMenu(
                $this->manialinkInterface,
                $tabsFrame,
                $this->menuItemProvider->getRootItem(),
                [$this, 'callbackItemClick'],
                'admin/admin'
            )
        );
        $mainFrame->addChild($tabsFrame);
        $mainFrame->addChild($frame);

        $closeButton = $this->uiFactory->createButton('Close', uiButton::TYPE_DECORATED);
        $closeButton->setBorderColor(uiButton::COLOR_WARNING)->setFocusColor(uiButton::COLOR_WARNING);
        $closeButton->setPosition(160 - ($closeButton->getWidth()/2), -90 - $mainFrame->getY());
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
        $quad->setAlign("left", "top")
            ->setStyles("Bgs1", "BgDialogBlur");
        $bgFrame->addChild($quad);

        $frame->addChild($bgFrame);

        $manialink->addChild($this->maniaScriptFactory->createScript(['']));

        return $closeButton;
    }

    /**
     * @param ManialinkInterface $manialinkInterface
     */
    public function setManialinkInterface(ManialinkInterface $manialinkInterface)
    {
        $this->manialinkInterface = $manialinkInterface;
    }

    /**
     * Callback when an item of the menu is clicked on.
     *
     * @param ManialinkInterface $manialink
     * @param $login
     * @param $params
     * @param $args
     */
    public function callbackItemClick(ManialinkInterface $manialink, $login, $params, $args)
    {
        $this->windowsGuiHandler->addToHide($manialink);
        $this->menuContentFactory->create($login);

        /** @var ItemInterface $item */
        $item = $args['item'];
        $item->execute($this->menuContentFactory, $manialink, $login, $params, $args);
    }
}
