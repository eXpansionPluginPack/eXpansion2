<?php

namespace eXpansion\Core;

use eXpansion\Core\DependencyInjection\Compiler\DataProviderPass;
use eXpansion\Core\DependencyInjection\Compiler\PluginPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class eXpansionCore extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DataProviderPass());
        $container->addCompilerPass(new PluginPass());
    }
}
