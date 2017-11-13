<?php

namespace eXpansion\Bundle\Menu\Plugins\Gui;

use eXpansion\Bundle\Menu\DataProviders\MenuItemProvider;
use eXpansion\Bundle\Menu\Gui\MenuTabsFactory;
use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;
use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;


/**
 * Class MenuContentFactory
 *
 * @package eXpansion\Bundle\Menu\Plugins\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class MenuContentFactory extends WidgetFactory
{
    /** @var  MenuItemProvider */
    protected $menuItemProvider;

    /** @var  ManiaScriptFactory */
    protected $menuScriptFactory;

    /** @var  MenuTabsFactory */
    protected $menuTabsFactory;

    /** @var string */
    protected $currentPath = 'admin';

    /**
     * MenuContentFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WidgetFactoryContext $context
     * @param MenuItemProvider     $menuItemProvider
     * @param MenuTabsFactory      $menuTabsFactory
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        ManiaScriptFactory $maniaScriptFactory,
        MenuItemProvider $menuItemProvider,
        MenuTabsFactory $menuTabsFactory
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->menuItemProvider = $menuItemProvider;
        $this->menuScriptFactory = $maniaScriptFactory;
        $this->menuTabsFactory = $menuTabsFactory;
    }

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $tabsFrame = new Frame('tabs');
        $tabsFrame->setPosition(-144, 82);
        $manialink->getContentFrame()->setZ(101);
        $manialink->getContentFrame()->addChild($tabsFrame);
        $manialink->setData('tabs_frame', $tabsFrame);

        $contentFrame = new Frame('menu_content');
        $contentFrame->setPosition(0, 72);
        $manialink->getContentFrame()->addChild($contentFrame);
        $manialink->setData('menu_content_frame', $contentFrame);

        $backGroundFrame = new Frame('background');
        $manialink->getContentFrame()->addChild($backGroundFrame);


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

        $manialink->getContentFrame()->addChild($bgFrame);

        /**
         * Adding script
         */
        $manialink->getFmlManialink()->addChild($this->menuScriptFactory->createScript([]));
    }

    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);

        $currentPath = $manialink->getData('current_path');
        if (is_null($currentPath)) {
            $currentPath = $this->currentPath;
            $manialink->setData('current_path', $currentPath);
        }
        $currentPath = trim($currentPath, '/');


        $rootItem = $this->menuItemProvider->getRootItem();
        $pathParts = explode('/', $currentPath);

        $this->createTabsMenu($manialink, $rootItem, $pathParts[0]);

        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getData('menu_content_frame');
        $contentFrame->removeAllChildren();

        $displayLevel = 0;
        for ($i = count($pathParts) - 1; $i >= 0; $i--) {
            $path = implode('/', array_slice($pathParts, 0, $i + 1));

            /** @var ParentItem $parentItem */
            $parentItem = $rootItem->getChild($path);

            $this->createSubMenu($manialink, $contentFrame, $parentItem, $displayLevel++);
        }
    }

    /**
     * Create tabs level menu.
     *
     * @param ManialinkInterface $manialink
     * @param ParentItem         $rootItem
     * @param                    $openId
     */
    protected function createTabsMenu(ManialinkInterface $manialink, ParentItem $rootItem, $openId)
    {
        /** @var Frame $tabsFrame */
        $tabsFrame = $manialink->getData('tabs_frame');
        $tabsFrame->removeAllChildren();

        $this->menuTabsFactory->createTabsMenu($manialink, $tabsFrame, $rootItem, $openId);
    }

    /**
     * Create content for sub menu.
     *
     * @param Manialink  $manialink
     * @param Frame      $frame
     * @param ParentItem $parentItem
     * @param            $displayLevel
     */
    protected function createSubMenu(Manialink $manialink, Frame $frame, ParentItem $parentItem, $displayLevel)
    {
        $posX = $displayLevel * (-160.0/3);
        $posY = ($displayLevel * (-100.0/3)) * 0.5;
        $scale = (0.5 / ($displayLevel + 1)) + 0.5;

        $contentFrame = new Frame();
        $contentFrame->setScale($scale);
        $contentFrame->setPosition($posX, $posY);
        $frame->addChild($contentFrame);

        if ($displayLevel > 0) {
            $overlay = new Quad();
            $overlay->setSize(60, 120);
            $overlay->setPosition(-30, 0);
            $overlay->setStyles("Bgs1", "BgDialogBlur");

            $action = $this->actionFactory->createManialinkAction(
                $manialink,
                [$this, 'callbackItemClick'],
                ['item' => $parentItem, 'ml' => $manialink]
            );


            $contentFrame->addChild($overlay);
            $overlay->setAction($action);
        }

        /* TITLE */
        $titleLabel = $this->uiFactory->createLabel($parentItem->getLabelId(), uiLabel::TYPE_TITLE);
        $titleLabel->setSize(60, 8);
        $titleLabel->setPosition(-30, 0);
        $titleLabel->setTranslate(true);
        $contentFrame->addChild($titleLabel);

        $titleLine = $this->uiFactory->createLine(-30, -8);
        $titleLine->to(30, -8);
        $contentFrame->addChild($titleLine);

        $posY = -12;
        foreach ($parentItem->getChilds() as $item) {
            if ($item->isVisibleFor($manialink->getUserGroup())) {
                $button = $this->uiFactory->createButton($item->getLabelId());
                $button->setPosition(-25, $posY);
                $button->setSize(50, 8);
                $button->setTranslate(true);

                if ($displayLevel == 0) {
                    $action = $this->actionFactory->createManialinkAction(
                        $manialink,
                        [$this, 'callbackItemClick'],
                        ['item' => $item, 'ml' => $manialink]
                    );
                    $button->setAction($action);
                }

                $contentFrame->addChild($button);
                $posY -= 12;
            }
        }

        if ($displayLevel == 0) {

            $button = $this->uiFactory->createButton('expansion_menu.menu_close', uiButton::TYPE_DECORATED);
            $button->setBackgroundColor(uiButton::COLOR_WARNING);
            $button->setPosition(-25, $posY - 12);
            $button->setSize(50, 8);
            $button->setTranslate(true);
            $action = $this->actionFactory->createManialinkAction($manialink, [$this, 'callbackClose'], ['ml' =>$manialink]);
            $button->setAction($action);
            $contentFrame->addChild($button);
        }
    }

    /**
     * Callback when an item of the menu is clicked on.
     *
     * @param $login
     * @param $params
     * @param $args
     */
    public function callbackItemClick($login, $params, $args)
    {
        /** @var ItemInterface $item */
        $item = $args['item'];
        $item->execute($this, $args['ml'], $login, $params, $args);
    }

    /**
     * Callback when the close button is clicked.
     *
     * @param $login
     * @param $params
     * @param $args
     */
    public function callbackClose($login, $params, $args)
    {
        $this->destroy($args['ml']->getUserGroup());
    }
}