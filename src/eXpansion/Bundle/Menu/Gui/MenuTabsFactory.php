<?php

namespace eXpansion\Bundle\Menu\Gui;

use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Label;
use FML\Types\Container;
use FML\Types\Renderable;

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
     * @param Factory $uiFactory
     * @param ActionFactory $actionFactory
     */
    public function __construct(Factory $uiFactory, ActionFactory $actionFactory)
    {
        $this->uiFactory = $uiFactory;
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param ManialinkInterface $manialink
     * @param Container $tabsFrame
     * @param ParentItem $rootItem
     * @param $openId
     *
     * @return Renderable
     */
    public function createTabsMenu(
        ManialinkInterface $manialink,
        Container $tabsFrame,
        ParentItem $rootItem,
        $actionCallback,
        $openId
    ) {

        $label = $this->uiFactory->createLabel("expansion_menu.menu");
        $label->setPosition(0, 0);
        $label->setSize(30, 5);
        $label->setTextSize(4);
        $label->setTextColor('FFFFFF');
        $label->setHorizontalAlign("center");
        $label->setTranslate(true);
        $tabsFrame->addChild($label);

        $posX = 28;
        foreach ($rootItem->getChilds() as $item) {
            if ($item->isVisibleFor($manialink->getUserGroup())) {
                $action = $this->actionFactory->createManialinkAction(
                    $manialink,
                    $actionCallback,
                    ['item' => $item, 'ml' => $manialink]
                );
                $label = $this->uiFactory->createLabel($item->getLabelId());

                $label->setPosition($posX, 0);
                $label->setSize(24, 5);
                $label->setAction($action);
                $label->setTextSize(3);
                $label->setTextColor('FFFFFF');
                $label->setHorizontalAlign(Label::CENTER);
                $label->setTranslate(true);

                if ($item->getId() == $openId) {
                    $underline = $this->uiFactory->createLine($posX - 13, -5);
                    $underline->to($posX + 13, -5);
                    $underline->setColor('FFFFFF');
                    $tabsFrame->addChild($underline);
                }

                $tabsFrame->addChild($label);
                $posX += 26;
            }
        }

        return $tabsFrame;
    }
}
