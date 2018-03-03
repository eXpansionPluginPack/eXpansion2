<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 10:04
 */

namespace eXpansion\Framework\Core\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class eXpansionCoreExtension extends Extension
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
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('listeners.yml');
        $loader->load('commands.yml');
        $loader->load('services.yml');
        $loader->load('data_providers.yml');
        $loader->load('storage.yml');
        $loader->load('user_groups.yml');
        $loader->load('ml_scripts.yml');
        $loader->load('gui.yml');
        $loader->load('gui_grid.yml');
        $loader->load('helpers.yml');
        $loader->load('configs.yml');

        // Temporary for the prototype.
        $loader->load('plugins.yml');

        if ($container->getParameter('kernel.environment') == 'dev') {
            $loader->load('plugins_dev.yml');
        } elseif ($container->getParameter('kernel.environment') == 'prod') {
            $loader->load('plugins_prod.yml');
        }
    }
}