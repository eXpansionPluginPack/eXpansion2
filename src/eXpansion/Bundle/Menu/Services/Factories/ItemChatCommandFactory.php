<?php

namespace eXpansion\Bundle\Menu\Services\Factories;

use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use eXpansion\Bundle\Menu\Services\ItemFactoryInterface;
use eXpansion\Framework\Core\DataProviders\ChatCommandDataProvider;
use FML\Controls\Quad;

/**
 * Class ItemChatCommandFactory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\Services\Factories
 */
class ItemChatCommandFactory implements ItemFactoryInterface
{

    /** @var ChatCommandDataProvider */
    protected $chatCommandProvider;

    /**
     * ItemChatCommandFactory constructor.
     *
     * @param ChatCommandDataProvider $chatCommandProvider
     */
    public function __construct(ChatCommandDataProvider $chatCommandProvider)
    {
        $this->chatCommandProvider = $chatCommandProvider;
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
        return $class == ChatCommandItem::class;
    }

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
    public function build($class, $id, $path, $label, Quad $icon, $permission, $options = [])
    {
        return new ChatCommandItem(
            $this->chatCommandProvider,
            $options['cmd'],
            $id,
            $path,
            $label,
            $icon,
            $permission
        );
    }
}