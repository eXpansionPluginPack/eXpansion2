<?php

namespace eXpansionExperimantal\Bundle\Dedimania\Plugins;

use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;


/**
 * Class MenuItems
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  reaby
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
            'records/dedimania',
            'expansion_dedimania.menu.dedi_recs',
            null,
            ['cmd' => '/dedirecs']
        );
    }
}