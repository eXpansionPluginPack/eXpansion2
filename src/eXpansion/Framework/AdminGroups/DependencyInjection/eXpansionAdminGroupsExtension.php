<?php

namespace eXpansion\Framework\AdminGroups\DependencyInjection;

use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class eXpansionAdminGroupsExtension extends Extension
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

        /** @noinspection PhpUndefinedClassInspection */
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('helpers.yml');
        $loader->load('services.yml');
        $loader->load('plugins.yml');

        $this->createConfigs($config['groups'], $config['permissions'], $container);
    }

    /**
     * Create the config services.
     *
     * @param $groups
     * @param ContainerBuilder $container
     */
    protected function createConfigs($groups, $permissions, ContainerBuilder $container)
    {
        $conifgManager = $container->getDefinition(ConfigManagerInterface::class);

        foreach ($groups as $groupCode => $group)
        {
            $pathPrefix = $container->getParameter('expansion.admin_groups.config.path') . "/$groupCode";

            $id = 'expansion.admin_groups.config.label.' . $groupCode;
            $container->setDefinition($id, new ChildDefinition('expansion.admin_groups.config.label.abstract'))
                ->setArgument('$path', "$pathPrefix/label")
                ->setArgument('$default', $group['label']);
            $conifgManager->addMethodCall('registerConfig', [new Reference($id), $id]);

            $id = 'expansion.admin_groups.config.logins.' . $groupCode;
            $container->setDefinition($id, new ChildDefinition('expansion.admin_groups.config.logins.abstract'))
                ->setArgument('$path', "$pathPrefix/logins")
                ->setArgument('$default', $group['logins']);
            $conifgManager->addMethodCall('registerConfig', [new Reference($id), $id]);

            if ($groupCode != "master_admin") {
                foreach ($permissions as $permission) {
                    $id = 'expansion.admin_groups.config.permissions.' . $groupCode . '.permission';
                    $container->setDefinition($id, new ChildDefinition('expansion.admin_groups.config.logins.abstract'))
                        ->setArgument('$path', "$pathPrefix/perm_perm")
                        ->setArgument('$default', $group['logins'])
                        ->setArgument('$name', "expansion_admingroups.permission.$permission.label")
                        ->setArgument('$description', "expansion_admingroups.permission.$permission.description");
                    $conifgManager->addMethodCall('registerConfig', [new Reference($id), $id]);
                }
            }
        }
    }
}
