<?php

namespace eXpansion\Bundle\Menu;

use eXpansion\Bundle\Menu\DependencyInjection\Compiler\ItemFactoryPass;
use eXpansion\Bundle\Menu\DependencyInjection\Compiler\Pass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MenuBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ItemFactoryPass());
    }
}
