<?php

namespace eXpansion\Core\DependencyInjection\Compiler;

use eXpansion\Core\Services\DataProviderManager;
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

        $providerData = [];

        // Get Procider service names.
        $dataProviders = $container->findTaggedServiceIds('expansion.data_provider');
        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerData[$id]['provider'] = $attributes['provider'];
                $providerData[$id]['interface'] = $attributes['interface'];
            }
        }

        // Get compatibility information for each provider
        $dataProviders = $container->findTaggedServiceIds('expansion.data_provider.compatibility');
        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerData[$id]['compatibility'][] = [
                    'title' => isset($attributes['title']) ? $attributes['title'] : DataProviderManager::COMPATIBLE_ALL,
                    'mode' => isset($attributes['mode']) ? $attributes['mode'] : DataProviderManager::COMPATIBLE_ALL,
                    'script' => isset($attributes['script']) ? $attributes['script'] : DataProviderManager::COMPATIBLE_ALL,
                ];
            }
        }

        $dataProviders = $container->findTaggedServiceIds('expansion.data_provider.listener');
        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerData[$id]['listener'][$attributes['event_name']] =  $attributes['method'];
            }
        }

        foreach ($providerData as $id => $data) {
            $definition->addMethodCall('registerDataProvider', [
                    $id,
                    $data['provider'],
                    $data['interface'],
                    $data['compatibility'],
                    $data['listener'],
                ]
            );
        }
    }
}