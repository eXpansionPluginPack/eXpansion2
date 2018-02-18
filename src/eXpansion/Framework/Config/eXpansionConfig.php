<?php

namespace eXpansion\Framework\Config;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class eXpansionConfig extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new \eXpansion\Framework\Config\DependencyInjection\Compiler\ConfigPass());
    }
}
