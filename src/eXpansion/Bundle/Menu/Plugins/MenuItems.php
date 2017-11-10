<?php

namespace eXpansion\Bundle\Menu\Plugins;

use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;
use FML\Controls\Quads\Quad_Icons64x64_1;
use FML\Controls\Quads\Quad_UIConstruction_Buttons;

/**
 * Class MenuItems
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\Plugins
 */
class MenuItems implements ListenerMenuItemProviderInterface
{
    /**
     * Register items tot he parent item.
     *
     * @param ParentItem $root
     *
     * @return mixed
     */
    public function registerMenuItems(ParentItem $root)
    {
        $root->addChild(
            ParentItem::class,
            'admin',
            'expansion_menu.admin.label',
            (new Quad_Icons64x64_1())->setSubStyle(Quad_Icons64x64_1::SUBSTYLE_StatePrivate),
            null // Permission are handled by sub elements.
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/help',
            'expansion_menu.admin.help',
            (new Quad_UIConstruction_Buttons())->setSubStyle(Quad_UIConstruction_Buttons::SUBSTYLE_Help),
            'admin',
            ['cmd' => '/help']
        );

        $root->addChild(
            ParentItem::class,
            'map',
            'expansion_menu.map.label',
            (new Quad_Icons64x64_1())->setSubStyle(Quad_Icons64x64_1::SUBSTYLE_StatePrivate),
            null // Permission are handled by sub elements.
        );
        $root->addChild(
            ChatCommandItem::class,
            'map/list',
            'expansion_menu.map.list',
            (new Quad_UIConstruction_Buttons())->setSubStyle(Quad_UIConstruction_Buttons::SUBSTYLE_Help),
            '',
            ['cmd' => '/list']
        );
        $root->addChild(
            ChatCommandItem::class,
            'map/list',
            'expansion_menu.map.mx',
            (new Quad_UIConstruction_Buttons())->setSubStyle(Quad_UIConstruction_Buttons::SUBSTYLE_Help),
            '',
            ['cmd' => '/mx']
        );
        $root->addChild(
            ChatCommandItem::class,
            'map/recs',
            'expansion_menu.map.recs',
            (new Quad_UIConstruction_Buttons())->setSubStyle(Quad_UIConstruction_Buttons::SUBSTYLE_Help),
            '',
            ['cmd' => '/recs']
        );
    }
}