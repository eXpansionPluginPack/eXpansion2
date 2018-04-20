<?php

namespace eXpansion\Framework\MlComposeBundle;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle
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
        $treeBuilder->root('ml_compose')
            ->children()
                ->prototype('array')->useAttributeAsKey("block_id")
                    ->scalarNode('alias')->end()
                    ->scalarNode('parent')->end()
                    ->scalarNode('component')->end()
                    ->scalarNode('extends')->end()
                    ->variableNode('config')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}