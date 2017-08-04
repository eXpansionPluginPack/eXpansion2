<?php

namespace eXpansion\Bundle\MxKarma\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * Class Configuration
 *
 * @package eXpansion\Framework\AdminGroups\DependencyInjection;
 * @author reaby
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
        $treeBuilder->root('e_xpansion_plugins')
            ->children()
                ->arrayNode('mxkarma')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('serverlogin')->prototype('scalar')->end()->end()
                            ->arrayNode('apikey')->prototype('scalar')->end()->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
