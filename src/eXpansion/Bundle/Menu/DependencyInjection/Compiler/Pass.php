<?php

namespace eXpansion\Bundle\Menu\DependencyInjection\Compiler;

use eXpansion\Bundle\Menu\Plugins\WindowsGuiHandler;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class Pass
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Bundle\Menu\DependencyInjection\Compiler
 */
class Pass implements CompilerPassInterface
{

    /**
     * Modifying the window factory context to use custom gui handler.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->getDefinition(WindowFactoryContext::class)
            ->setArgument('$guiHandler', new Reference(WindowsGuiHandler::class));
    }
}
