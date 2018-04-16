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
            'admin',
            'expansion_menu.admin.label',
            null
        );
        $root->addChild(
            ParentItem::class,
            'admin/server',
            'expansion_admin.menu.server',
            null
        );
        $root->addChild(
            ParentItem::class,
            'admin/server/config',
            'expansion_config.menu.label',
            null
        );

        $configTree = $this->configManager->getConfigDefinitionTree();
        $this->registerConfigItems($root, 'admin/server/config', $configTree->getArray());
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
        foreach ($configItems as $configId => $configItem) {
            $subItems = reset($configItem);
            $path = $parentId . '/' . $configId;
            $configPath = str_replace("admin/server/config/",'', $path);
            $translationKey = 'expansion_config.menu.' . implode('.', explode('/', $configPath)) . '.label';

            if (is_array($subItems)) {
                $root->addChild(
                    ParentItem::class,
                    $path,
                    $translationKey,
                    null
                );

                $this->registerConfigItems($root, $path, $configItem);
            } else {
                $root->addChild(
                    ChatCommandItem::class,
                    $path,
                    $translationKey,
                    'admin_config', // Default config on each element.
                    ['cmd' => '/admin config "' . $configPath . '"']
                );
            }
        }
    }
}
