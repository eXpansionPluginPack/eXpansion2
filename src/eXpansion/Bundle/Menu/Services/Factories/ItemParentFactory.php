<?php

namespace eXpansion\Bundle\Menu\Services\Factories;

use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;
use eXpansion\Bundle\Menu\Services\ItemBuilder;
use eXpansion\Bundle\Menu\Services\ItemFactoryInterface;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use FML\Controls\Quad;

/**
 * Class ItemParentFactory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\Services
 */
class ItemParentFactory implements ItemFactoryInterface
{
    /** @var ItemBuilder */
    protected $itemBuilder;

    /** @var AdminGroups */
    protected $adminGroups;

    /**
     * ItemParentFactory constructor.
     *
     * @param ItemBuilder $itemBuilder
     */
    public function __construct(AdminGroups $adminGroups, ItemBuilder $itemBuilder)
    {
        $this->itemBuilder = $itemBuilder;
        $this->adminGroups = $adminGroups;
    }

    /**
     * Check if item factory supports building a certain class.
     *
     * @param string $class
     *
     * @return boolean
     */
    public function supports($class)
    {
       return $class == ParentItem::class;
    }

    /**
     * Creates a new Menu item
     *
     * @param string $class Class of the item.
     * @param string $id Id of the item
     * @param string $path Path of the item
     * @param string $label
     * @param string $permission
     * @param array $options
     *
     * @return ItemInterface
     */
    public function build($class, $id, $path, $label, $permission, $options = [])
    {
        return new ParentItem(
            $this->itemBuilder,
            $id,
            $path,
            $label,
            $this->adminGroups,
            $permission
        );
    }
}
