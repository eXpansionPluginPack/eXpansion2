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
            null // Permission are handled by sub elements.
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/help',
            'expansion_menu.admin.help',
            'admin',
            ['cmd' => '/help']
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/script_settings',
            'expansion_menu.admin.script_settings',
            'admin',
            ['cmd' => '/admin script']
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/server_settings',
            'expansion_menu.admin.server_settings',
            'admin',
            ['cmd' => '/admin server']
        );

        /*
         * @TODO put these in plugins that actually defines the commands.
         */
        $root->addChild(
            ParentItem::class,
            'map',
            'expansion_menu.map.label',
            null // Permission are handled by sub elements.
        );
        $root->addChild(
            ChatCommandItem::class,
            'map/list',
            'expansion_menu.map.list',
            '',
            ['cmd' => '/list']
        );
        $root->addChild(
            ChatCommandItem::class,
            'map/mx',
            'expansion_menu.map.mx',
            '',
            ['cmd' => '/mx']
        );
        $root->addChild(
            ChatCommandItem::class,
            'map/recs',
            'expansion_menu.map.recs',
            '',
            ['cmd' => '/recs']
        );

        /**
         * Test sub and subs.
         *
         */
        $root->addChild(
            ParentItem::class,
            'admin/sub',
            'expansion_menu.admin.sub1',
            'admin',
            []
        );
        $root->addChild(
            ParentItem::class,
            'admin/sub/sub',
            'expansion_menu.admin.sub2',
            'admin',
            []
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/sub/sub_button',
            'expansion_menu.map.recs',
            '',
            ['cmd' => '/recs']
        );
        $root->addChild(
            ParentItem::class,
            'admin/sub/sub/sub',
            'expansion_menu.admin.sub3',
            'admin',
            []
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/sub/sub/sub_button',
            'expansion_menu.map.recs',
            '',
            ['cmd' => '/recs']
        );
    }
}