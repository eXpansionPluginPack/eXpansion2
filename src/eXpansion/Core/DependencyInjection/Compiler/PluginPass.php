<?php

namespace eXpansion\Core\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * PluginPass to register all plugins to the plugin manager.
 *
 * @package eXpansion\Core\DependencyInjection\Compiler
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
        if (!$container->has('expansion.core.services.plugin_manager')) {
            return;
        }

        // Get the data provider manager service definition to register data providers into.
        $definition = $container->getDefinition('expansion.core.services.plugin_manager');

        // Find all Data Provider services.
        $plugins = $container
            ->findTaggedServiceIds('expansion.plugin');

        // Finally register all the plugins.
        foreach ($plugins as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('registerPlugin', [
                        $id,
                        $attributes['data_provider'],
                    ]
                );
            }
        }
    }
}