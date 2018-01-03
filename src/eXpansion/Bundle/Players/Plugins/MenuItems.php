<?php

namespace eXpansion\Bundle\Players\Plugins;

use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;


/**
 * Class MenuItems
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
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
            'players',
            'expansion_players.menu.label',
            null // Permission are handled by sub elements.
        );
        $root->addChild(
            ChatCommandItem::class,
            'players/list',
            'expansion_players.menu.list',
            null,
            ['cmd' => '/players']
        );
    }
}
