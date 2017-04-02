<?php

namespace eXpansion\Framework\AdminGroups\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * Class Configuration
 *
 * @package eXpansion\Framework\AdminGroups\DependencyInjection;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class Configuration implements ConfigurationInterface
{

    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('e_xpansion_admin_groups')
            ->children()
                ->arrayNode('groups')
                    ->prototype('array')
                        ->children()
                            ->variableNode('label')->end()
                            ->arrayNode('logins')->prototype('scalar')->end()->end()
                            ->arrayNode('permissions')->prototype('scalar')->end()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}