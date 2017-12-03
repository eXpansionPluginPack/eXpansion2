<?php


namespace eXpansion\Bundle\AdminChat\Plugins;

use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;


/**
 * Class MenuItems
 *
 * @package eXpansion\Bundle\AdminChat\Plugins;
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
            ChatCommandItem::class,
            'admin/restartmap',
            'expansion_admin_chat.restartmap.label',
            'restart',
            ['cmd' => '/admin restart']
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/nextmap',
            'expansion_admin_chat.nextmap.label',
            'next',
            ['cmd' => '/admin next']
        );
        $root->addChild(
            ChatCommandItem::class,
            'admin/cancel',
            'expansion_admin_chat.cancel.label',
            'votes',
            ['cmd' => '/admin cancel']
        );
    }
}