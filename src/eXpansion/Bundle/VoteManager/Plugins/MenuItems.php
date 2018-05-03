<?php

namespace eXpansion\Bundle\VoteManager\Plugins;

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
            ChatCommandItem::class,
            'general/restart',
            'expansion_votemanager.menu.restart',
            null,
            ['cmd' => '/res']
        );

        $root->addChild(
            ChatCommandItem::class,
            'general/skip',
            'expansion_votemanager.menu.skip',
            null,
            ['cmd' => '/skip']
        );
    }
}
