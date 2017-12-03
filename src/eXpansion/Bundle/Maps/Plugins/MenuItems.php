<?php


namespace eXpansion\Bundle\Maps\Plugins;
use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;


/**
 * Class MenuItems
 *
 * @package eXpansion\Bundle\Maps\Plugins;
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
            'maps',
            'expansion_maps.menu.label',
            null
        );
        $root->addChild(
            ChatCommandItem::class,
            'maps/list',
            'expansion_maps.menu.list',
            null,
            ['cmd' => '/list']
        );
        $root->addChild(
            ChatCommandItem::class,
            'maps/jukebox',
            'expansion_maps.menu.jukebox',
            null,
            ['cmd' => '/jukebox']
        );
        $root->addChild(
            ChatCommandItem::class,
            'maps/searchmx',
            'expansion_maps.menu.searchmx',
            null,
            ['cmd' => '/admin search']
        );
    }
}