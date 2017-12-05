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
 * @copyright 2017 eXpansion
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
            'general',
            'expansion_menu.general.label',
            null
        );
        $root->addChild(
            ChatCommandItem::class,
            'general/help',
            'expansion_menu.general.help',
            null,
            ['cmd' => '/help']
        );
    }
}