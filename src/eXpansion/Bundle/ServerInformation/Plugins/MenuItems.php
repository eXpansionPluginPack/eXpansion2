<?php
/**
 * @author    Wide Agency Dev Team
 * @category  eXpansion2
 */

namespace eXpansion\Bundle\ServerInformation\Plugins;


use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;

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
            'general/about',
            'expansion_server_information.menu.label',
            null,
            ['cmd' => '/server']
        );
    }
}
