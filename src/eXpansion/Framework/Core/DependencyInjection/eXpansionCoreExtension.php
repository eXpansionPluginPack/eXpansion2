<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 10:04
 */

namespace eXpansion\Framework\Core\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

class eXpansionCoreExtension extends Extension implements PrependExtensionInterface
{

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        foreach ($bundles as $bundle) {
            $reflection = new \ReflectionClass($bundle);

            if (is_file($file = dirname($reflection->getFilename()) . '/Resources/config/expansion_defaults/core_config.yml')) {
                $config = Yaml::parse(file_get_contents(realpath($file)));
                $container->prependExtensionConfig('e_xpansion_core', $config['e_xpansion_core']);
            }
        }

        if (is_file($file = $container->getParameter('kernel.root_dir') . '/config/expansion.yml')) {
            $config = Yaml::parse(file_get_contents(realpath($file)));
            $container->prependExtensionConfig('e_xpansion_core', $config['e_xpansion_core']);
        }
    }

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
        $container->setParameter("expansion.core.widget_positions", $config['widget_positions']);

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