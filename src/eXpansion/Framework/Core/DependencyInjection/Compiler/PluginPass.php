<?php

namespace eXpansion\Framework\Core\DependencyInjection\Compiler;

use eXpansion\Framework\Core\Services\PluginManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * PluginPass to register all plugins to the plugin manager.
 *
 * @package eXpansion\Framework\Core\DependencyInjection\Compiler
 */
class PluginPass implements CompilerPassInterface
{
    /**
     * Register all data providers to the Manager.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(PluginManager::class)) {
            return;
        }

        $pluginsData = [];

        // Find all Plugin services.
        $plugins = $container->findTaggedServiceIds('expansion.plugin');
        foreach ($plugins as $id => $tags) {
            $pluginsData[$id]['dataProviders'] = [];
            foreach ($tags as $attributes) {
                if (isset($attributes['data_provider'])) {
                    $pluginsData[$id]['dataProviders'][] = $attributes['data_provider'];
                }
            }
        }

        // Find the parent services.
        $plugins = $container->findTaggedServiceIds('expansion.plugin.parent');
        foreach ($plugins as $id => $tags) {
            foreach ($tags as $attributes) {
                $pluginsData[$id]['parent'][] = $attributes['parent'];
            }
        }

        // Get the data provider manager service definition to register data providers into.
        $definition = $container->getDefinition(PluginManager::class);

        foreach ($pluginsData as $pluginId => $data)
        {
            $definition->addMethodCall('registerPlugin', [
                    $pluginId,
                    empty($data['dataProviders']) ? [] : $data['dataProviders'],
                    empty($data['parent']) ? [] : $data['parent'],
                ]
            );
        }
    }
}
