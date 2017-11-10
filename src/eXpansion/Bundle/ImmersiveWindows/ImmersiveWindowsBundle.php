<?php

namespace eXpansion\Bundle\ImmersiveWindows;

use eXpansion\Framework\Core\DependencyInjection\Compiler\DataProviderPass;
use eXpansion\Framework\Core\DependencyInjection\Compiler\PluginPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EmotesBundle
 *
 * @package eXpansion\Bundle\Emotes
 */
class ImmersiveWindowsBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Pass());
    }
}
