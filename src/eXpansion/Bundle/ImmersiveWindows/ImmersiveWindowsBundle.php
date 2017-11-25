<?php

namespace eXpansion\Bundle\ImmersiveWindows;

use eXpansion\Bundle\ImmersiveWindows\DependencyInjection\Compiler\Override;
use eXpansion\Bundle\Menu\MenuBundle;
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

        $container->addCompilerPass(new Override());
    }

    /**
     * Gets the list of bundle dependencies.
     *
     * @return array
     */
    public function getBundleDependencies()
    {
        return [
            MenuBundle::class
        ];
    }
}
