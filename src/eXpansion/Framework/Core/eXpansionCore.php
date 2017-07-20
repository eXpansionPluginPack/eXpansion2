<?php

namespace eXpansion\Framework\Core;

use eXpansion\Framework\Core\DependencyInjection\Compiler\DataProviderPass;
use eXpansion\Framework\Core\DependencyInjection\Compiler\PluginPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class eXpansionCore extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DataProviderPass());
        $container->addCompilerPass(new PluginPass());
    }
}
