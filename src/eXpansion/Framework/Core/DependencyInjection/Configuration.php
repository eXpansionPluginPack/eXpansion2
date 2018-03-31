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
                ->arrayNode('widget_positions')
                    ->prototype('array') // Id of the widget.
                        ->prototype('array') // Title
                            ->prototype('array') // Game mode
                                ->prototype('array')->children() // Script
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