<?php


namespace eXpansion\Framework\Core\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;


/**
 * Class Configuration
 *
 * @package eXpansion\Framework\Core\DependencyInjection;
 * @author  oliver de Cramer <oliverde8@gmail.com>
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
        $treeBuilder->root('e_xpansion_core')
            ->children()
                ->arrayNode('widget_positions')->useAttributeAsKey("widget_id")
                    ->prototype('array')->useAttributeAsKey("title_id")
                        ->prototype('array')->useAttributeAsKey('game_mode')
                            ->prototype('array') ->useAttributeAsKey("script")
                                ->prototype('array')->children()
                                    ->floatNode('posX')->end()
                                    ->floatNode('posY')->end()
                                    ->variableNode('options')->end()
                                ->end()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}