<?php

namespace eXpansion\Bundle\Menu\DependencyInjection\Compiler;

use eXpansion\Bundle\Menu\Services\ItemBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ItemFactoryPass
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\Menu\DependencyInjection\Compiler
 */
class ItemFactoryPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $builderDefinition = $container->getDefinition(ItemBuilder::class);
        $factories = $container->findTaggedServiceIds('expansion.menu.item.factory');

        foreach ($factories as $id => $tags) {
            $builderDefinition->addMethodCall(
                "addItemFactory",
                [new Reference($id)]
            );
        }
    }
}