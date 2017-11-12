<?php

namespace eXpansion\Bundle\Menu\Services\Factories;

use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ItemInterface;
use eXpansion\Bundle\Menu\Services\ItemFactoryInterface;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
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

    /** @var AdminGroups */
    protected $adminGroups;

    /**
     * ItemChatCommandFactory constructor.
     *
     * @param ChatCommandDataProvider $chatCommandProvider
     */
    public function __construct(AdminGroups $adminGroups, ChatCommandDataProvider $chatCommandProvider)
    {
        $this->chatCommandProvider = $chatCommandProvider;
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
        return $class == ChatCommandItem::class;
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
        return new ChatCommandItem(
            $this->chatCommandProvider,
            $options['cmd'],
            $id,
            $path,
            $label,
            $this->adminGroups,
            $permission
        );
    }
}