<?php

namespace eXpansion\Bundle\Menu\DataProviders;

use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;
use eXpansion\Bundle\Menu\Services\ItemBuilder;
use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;
use FML\Controls\Quad;

/**
 * Class MenuItemProvider
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\DataProviders
 */
class MenuItemProvider extends AbstractDataProvider
{
    /** @var ItemBuilder */
    protected $itemBuilder;

    /** @var ItemInterface|null */
    protected $rootItem = null;

    /**
     * MenuItemProvider constructor.
     *
     * @param ItemBuilder $itemBuilder
     */
    public function __construct(ItemBuilder $itemBuilder)
    {
        $this->itemBuilder = $itemBuilder;
    }


    public function registerPlugin($pluginId, $pluginService)
    {
        $this->rootItem = null;
        parent::registerPlugin($pluginId, $pluginService);
    }

    public function deletePlugin($pluginId)
    {
        $this->rootItem = null;
        parent::deletePlugin($pluginId);
    }

    public function getRootItem()
    {
        if (is_null($this->rootItem)) {
            $this->rootItem = $this->itemBuilder->create(
                ParentItem::class, "", "root", "root", Quad::create(), null
            );
            foreach ($this->plugins as $plugin) {
                $plugin->registerMenuItems($this->rootItem);
            }
        }

        return $this->rootItem;
    }
}