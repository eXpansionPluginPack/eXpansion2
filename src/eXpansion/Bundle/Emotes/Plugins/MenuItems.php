<?php


namespace eXpansion\Bundle\Emotes\Plugins;

use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;

/**
 * Class MenuItems
 *
 * @package eXpansion\Bundle\Emotes\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
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
            ParentItem::class,
            'general/emotes',
            'expansion_emotes.menu.label',
            null
        );

        $root->addChild(
            ChatCommandItem::class,
            'general/emotes/gg',
            'expansion_emotes.menu.gg',
            null,
            ['cmd' => '/gg']
        );
        $root->addChild(
            ChatCommandItem::class,
            'general/emotes/bb',
            'expansion_emotes.menu.bb',
            null,
            ['cmd' => '/bb']
        );
        $root->addChild(
            ChatCommandItem::class,
            'general/emotes/thx',
            'expansion_emotes.menu.thx',
            null,
            ['cmd' => '/thx']
        );

    }
}