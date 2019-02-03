<?php


namespace eXpansion\Bundle\InfoMessages\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ConfigPass
 *
 * @package eXpansion\Bundle\InfoMessages\DependencyInjection\Compiler;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ConfigPass implements CompilerPassInterface
{

    /**
     * Register a text list for each locale.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {

    }
}
