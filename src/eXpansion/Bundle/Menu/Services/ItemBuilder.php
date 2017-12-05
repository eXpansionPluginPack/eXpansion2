<?php

namespace eXpansion\Bundle\Menu\Services;

use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use FML\Controls\Quad;

/**
 * Class ItemFactory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\Menu\Services
 */
class ItemBuilder
{
    /** @var ItemFactoryInterface[] */
    protected $itemFactories = [];

    /**
     * @param ItemFactoryInterface $itemFactory
     */
    public function addItemFactory(ItemFactoryInterface $itemFactory)
    {
        $this->itemFactories[] = $itemFactory;
    }

    /**
     * @param string $class
     * @param string $id
     * @param string $path
     * @param string $label
     * @param string $permission
     * @param array $options
     *
     * @return ItemInterface
     */
    public function create($class, $id, $path, $label, $permission, $options =[])
    {
        foreach ($this->itemFactories as $itemFactory) {
            if ($itemFactory->supports($class)) {
                return $itemFactory->build($class, $id, $path, $label, $permission, $options);
            }
        }
    }
}
