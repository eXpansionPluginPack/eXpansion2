<?php

namespace eXpansion\Bundle\Menu\Gui;

use eXpansion\Bundle\Menu\Gui\Elements\MenuTabItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Types\Container;

/**
 * Class MenuTabsFactory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\Maps\Gui
 */
class MenuTabsFactory
{
    /** @var Factory */
    protected $uiFactory;

    /** @var ActionFactory */
    protected $actionFactory;

    /**
     * MenuTabsFactory constructor.
     *
     * @param Factory       $uiFactory
     * @param ActionFactory $actionFactory
     */
    public function __construct(Factory $uiFactory, ActionFactory $actionFactory)
    {
        $this->uiFactory = $uiFactory;
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param ManialinkInterface $manialink
     * @param Container          $tabsFrame
     * @param ParentItem         $rootItem
     * @param                    $openId
     *
     * @return Container
     */
    public function createTabsMenu(
        ManialinkInterface $manialink,
        Container $tabsFrame,
        ParentItem $rootItem,
        $actionCallback,
        $openId
    ) {

        foreach ($rootItem->getChilds() as $item) {
            if ($item->isVisibleFor($manialink->getUserGroup())) {

                $action = $this->actionFactory->createManialinkAction(
                    $manialink,
                    $actionCallback,
                    ['item' => $item, 'ml' => $manialink]
                );

                $tabItem = new MenuTabItem($item->getLabelId(), $action);
                $tabItem->setSize(24, 6);

                if ($item->getId() == $openId) {
                    $tabItem->setActive(true);
                }

                $tabsFrame->addChild($tabItem);
            }
        }

        $tabsFrame->setX(-1 * $tabsFrame->getWidth() / 2);

        return $tabsFrame;
    }
}
