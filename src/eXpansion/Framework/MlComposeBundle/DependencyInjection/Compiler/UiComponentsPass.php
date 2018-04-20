<?php

namespace eXpansion\Framework\MlComposeBundle\DependencyInjection\Compiler;

use Oliverde8\PageCompose\Service\UiComponents;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class UiComponentsPass
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\DependencyInjection\Compiler
 */
class UiComponentsPass implements CompilerPassInterface
{
    /**
     * Register all data providers to the Manager.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $componentsDefinition = $container->getDefinition(UiComponents::class);

        $components = $container->findTaggedServiceIds('exp.ui.component');
        foreach ($components as $id => $tags) {
            foreach ($tags as $attributes) {
                $componentsDefinition->addMethodCall('registerUiComponent', [$attributes['type'], new Reference($id)]);
            }
        }
    }

}