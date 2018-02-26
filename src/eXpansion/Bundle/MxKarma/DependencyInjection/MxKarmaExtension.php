<?php

namespace eXpansion\Bundle\MxKarma\DependencyInjection;

use eXpansion\Bundle\MxKarma\DependencyInjection\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MxKarmaExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array            $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container,
            new FileLocator([__DIR__.'/../Resources/config', './app/config/plugins']));
        $loader->load('configs.yml');
        $loader->load('plugins.yml');
        $loader->load('services.yml');
    }
}
