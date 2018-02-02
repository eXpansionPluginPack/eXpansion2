<?php

namespace eXpansion\Framework\Config\Plugins;

use eXpansion\Bundle\Menu\DataProviders\Listener\ListenerMenuItemProviderInterface;
use eXpansion\Bundle\Menu\Model\Menu\ChatCommandItem;
use eXpansion\Bundle\Menu\Model\Menu\ParentItem;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;

/**
 * Class MenuItems
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Plugins
 */
class MenuItems implements ListenerMenuItemProviderInterface
{
    /** @var ConfigManagerInterface */
    protected $configManager;

    /**
     * MenuItems constructor.
     *
     * @param ConfigManagerInterface $configManager
     */
    public function __construct(ConfigManagerInterface $configManager)
    {
        $this->configManager = $configManager;
    }


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
            'admin/config',
            'expansion_config.menu.label',
            null
        );

        $configTree = $this->configManager->getConfigDefinitionTree();
        $this->registerConfigItems($root, 'admin/config', $configTree->getArray());
    }

    /**
     * Register config items
     *
     * @param ParentItem $root
     * @param $parentId
     * @param $configItems
     */
    protected function registerConfigItems(ParentItem $root, $parentId, $configItems)
    {
        $firstElement = reset($configItems);
        if (is_array($firstElement)) {
            foreach ($configItems as $configId => $configItem) {
                $path = $parentId . '/' . $configId;
                if (!$root->getChild($path)) {
                    $root->addChild(
                        ParentItem::class,
                        $path,
                        'expansion_config.menu.' . $configId,
                        null
                    );
                }

                $this->registerConfigItems($root, $path, $configItem);
            }
        } else {
            $root->addChild(
                ChatCommandItem::class,
                $parentId,
                'expansion_config.menu.' . implode('.', explode('/', $parentId)),
                null,
                ['cmd' => '/admin config "' . $parentId . "'"]
            );
        }
    }
}
