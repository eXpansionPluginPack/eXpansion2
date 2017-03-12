<?php

namespace eXpansion\Core\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DataProviderPass implements CompilerPassInterface
{
    /**
     * Register all data providers to the Manager.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('expansion.core.services.data_provider_manager')) {
            return;
        }

        // Get the data provider manager service definition to register plugins into.
        $definition = $container->getDefinition('expansion.core.services.data_provider_manager');

        // Find all Data Provider services.
        $dataProviders = $container
            ->findTaggedServiceIds('expansion.data_provider');

        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('registerDataProvider', [
                        $id,
                        $attributes['provider'],
                        isset($attributes['title']) ? $attributes['title'] : '',
                        isset($attributes['mode']) ? $attributes['mode'] : '',
                        isset($attributes['script']) ? $attributes['script'] : '',
                    ]
                );
            }
        }

        // Find all Data Provider services.
        $dataProviders = $container
            ->findTaggedServiceIds('expansion.data_listener');

        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('registerDataProviderListener', [
                        $id,
                        $attributes['event_name'],
                        $attributes['method'],
                    ]
                );
            }
        }
    }
}