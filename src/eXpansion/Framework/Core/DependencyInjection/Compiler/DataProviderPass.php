<?php

namespace eXpansion\Framework\Core\DependencyInjection\Compiler;

use eXpansion\Framework\Core\Services\DataProviderManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * DataProviderPass Register all data providers to the Manager.
 *
 * @package eXpansion\Framework\Core\DependencyInjection\Compiler
 */
class DataProviderPass implements CompilerPassInterface
{
    /**
     * Register all data providers to the Manager.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('expansion.service.data_provider_manager')) {
            return;
        }

        // Get the data provider manager service definition to register plugins into.
        $dpmDefinition = $container->getDefinition('expansion.service.data_provider_manager');
        $pmDefinition = $container->getDefinition('expansion.service.plugin_manager');

        $providerData = [];

        // Get provider service names.
        $dataProviders = $container->findTaggedServiceIds('expansion.dataprovider');
        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerData[$id]['provider'] = $attributes['provider'];
                $providerData[$id]['interface'] = $attributes['interface'];
            }
        }

        // Get compatibility information for each provider
        $dataProviders = $container->findTaggedServiceIds('expansion.dataprovider.compatibility');
        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerData[$id]['compatibility'][] = [
                    'title' => isset($attributes['title']) ? $attributes['title'] : DataProviderManager::COMPATIBLE_ALL,
                    'mode' => isset($attributes['mode']) ? $attributes['mode'] : DataProviderManager::COMPATIBLE_ALL,
                    'script' => isset($attributes['script']) ? $attributes['script'] : DataProviderManager::COMPATIBLE_ALL,
                ];
            }
        }

        // Get base events the data provider needs to listen to.
        $dataProviders = $container->findTaggedServiceIds('expansion.dataprovider.listener');
        foreach ($dataProviders as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerData[$id]['listener'][$attributes['event_name']] = $attributes['method'];
            }
        }

        // Get parent plugins the data provider requires.
        $plugins = $container->findTaggedServiceIds('expansion.dataprovider.parent');
        foreach ($plugins as $id => $tags) {
            foreach ($tags as $attributes) {
                $providerData[$id]['parent'][] = $attributes['parent'];
            }
        }

        // Finally register collected data.
        foreach ($providerData as $id => $data) {
            $dpmDefinition->addMethodCall('registerDataProvider', [
                    $id,
                    $data['provider'],
                    $data['interface'],
                    $data['compatibility'],
                    !empty($data['listener']) ? $data['listener'] : [],
                ]
            );

            $pmDefinition->addMethodCall('registerPlugin', [
                    $id,
                    [],
                    isset($data['parent']) ? $data['parent'] : [],
                    $data['provider'],
                ]
            );
        }
    }
}
