<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 10:04
 */

namespace eXpansion\Framework\Core\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class eXpansionCoreExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['parameters'] as $paramName => $value) {
            $container->setParameter("expansion.config.$paramName", $value);
        }


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('commands.yml');
        $loader->load('services.yml');
        $loader->load('data_providers.yml');
        $loader->load('storage.yml');
        $loader->load('user_groups.yml');
        $loader->load('ml_scripts.yml');
        $loader->load('gui.yml');
        $loader->load('gui_grid.yml');
        $loader->load('helpers.yml');
        $loader->load('listeners.yml');

        // Temporary for the prototype.
        $loader->load('plugins.yml');

        if ($container->getParameter('kernel.environment') == 'dev') {
            $loader->load('plugins_dev.yml');
        }
    }
}