<?php

namespace eXpansion\Bundle\Menu\Services;

use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use FML\Controls\Quad;


/**
 * Interface ItemFactoryInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\Services
 */
interface ItemFactoryInterface
{

    /**
     * Check if item factory supports building a certain class.
     *
     * @param string $class
     *
     * @return boolean
     */
    public function supports($class);

    /**
     * Creates a new Menu item
     *
     * @param string $class Class of the item.
     * @param string $id Id of the item
     * @param string $path Path of the item
     * @param string $label
     * @param Quad $icon
     * @param string $permission
     * @param array $options
     *
     * @return ItemInterface
     */
    public function build($class, $id, $path, $label, Quad $icon, $permission, $options =[]);
}