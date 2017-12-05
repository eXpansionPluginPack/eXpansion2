<?php

namespace eXpansion\Bundle\Menu\DataProviders\Listener;

use eXpansion\Bundle\Menu\Model\Menu\ParentItem;

/**
 * Interface MenuItemProviderInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\Menu\DataProviders\Listener
 */
interface ListenerMenuItemProviderInterface
{
    /**
     * Register items tot he parent item.
     *
     * @param ParentItem $root
     *
     * @return mixed
     */
    public function registerMenuItems(ParentItem $root);
}
