<?php

namespace eXpansion\Framework\Config\DependencyInjection\Compiler;

use eXpansion\Framework\Config\Services\ConfigManager;
use eXpansion\Framework\Config\Services\ConfigUiManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * PluginPass to register all plugins to the plugin manager.
 *
 * @package eXpansion\Framework\Core\DependencyInjection\Compiler
 */
class ConfigPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * Register all data providers to the Manager.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ConfigManager::class)) {
            return;
        }
        $definition = $container->getDefinition(ConfigManager::class);

        // Find all config's
        $configs = $container->findTaggedServiceIds('expansion.config');
        foreach ($configs as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'registerConfig',
                    [new Reference($id), $id]
                );
            }
        }

        // Find all services to display configs.
        $definition = $container->getDefinition(ConfigUiManager::class);
        $services = $this->findAndSortTaggedServices('expansion.config.ui', $container);
        foreach($services as $service) {
            $definition->addMethodCall(
                'registerUi',
                [$service]
            );
        }
    }
}
