<?php

namespace eXpansion\Bundle\LocalRecords\Plugins;

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
            'records',
            'expansion_local_records.menu.label',
            null // Permission are handled by sub elements.
        );
        $root->addChild(
            ChatCommandItem::class,
            'records/list',
            'expansion_local_records.menu.race_recs',
            null,
            ['cmd' => '/recs']
        );
        $root->addChild(
            ChatCommandItem::class,
            'records/laps',
            'expansion_local_records.menu.laps_recs',
            null,
            ['cmd' => '/dummy']
        );
        $root->addChild(
            ChatCommandItem::class,
            'records/prev_list',
            'expansion_local_records.menu.prev.race_recs',
            null,
            ['cmd' => '/dummy']
        );
        $root->addChild(
            ChatCommandItem::class,
            'records/prev_laps',
            'expansion_local_records.menu.prev.laps_recs',
            null,
            ['cmd' => '/dummy']
        );
    }
}