<?php


namespace eXpansion\Bundle\Admin\Plugins;
use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;


/**
 * Class MenuItems
 *
 * @package eXpansion\Bundle\Admin\Plugins;
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
            'admin',
            'expansion_menu.admin.label',
            null // Permission are handled by sub elements.
        );

        $root->addChild(
            ParentItem::class,
            'admin/server',
            'expansion_admin.menu.server',
            null // Permission are handled by sub elements.
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/server/server_settings',
            'expansion_admin.menu.server_settings',
            'server',
            ['cmd' => '/admin server']
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/server/script_settings',
            'expansion_admin.menu.script_settings',
            'script',
            ['cmd' => '/admin script']
        );
    }
}
