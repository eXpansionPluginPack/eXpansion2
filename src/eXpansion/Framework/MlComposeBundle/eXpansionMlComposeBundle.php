<?php

namespace eXpansion\Framework\MlComposeBundle;

use eXpansion\Framework\MlComposeBundle\DependencyInjection\Compiler\UiComponentsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class eXpansionMlComposeBundle
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle
 */
class eXpansionMlComposeBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new UiComponentsPass());
    }
}