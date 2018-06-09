<?php

namespace eXpansion\Framework\AdminGroups\DependencyInjection\Compiler;

use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ConfigPass
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Framework\AdminGroups\DependencyInjection\Compiler
 */
class ConfigPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('expansion.admin_groups.raw.configs');

        // Replace permissions read in config with those coming for service declarations
        $permissions = $this->loadPermissions($container);
        $config['permissions'] = $permissions;
        $container->setParameter('expansion.admin_groups.raw.configs', $config);

        $this->createConfigs($config['groups'], $config['permissions'], $container);
    }

    /**
     * @param ContainerBuilder $container
     *
     * @return array
     */
    protected function loadPermissions(ContainerBuilder $container)
    {
        $permissions = [];

        $permissionServices = $container->findTaggedServiceIds("exp.permission");
        foreach ($permissionServices as $tags) {
            foreach ($tags as $attributes) {
                $permissions[] = $attributes['permission'];
            }
        }

        return $permissions;
    }

        /**
     * Create the config services.
     *
     * @param $groups
     * @param ContainerBuilder $container
     */
    protected function createConfigs($groups, $permissions, ContainerBuilder $container)
    {
        $configManager = $container->findDefinition(ConfigManagerInterface::class);

        foreach ($groups as $groupCode => $group)
        {
            $pathPrefix = $container->getParameter('expansion.admin_groups.config.path') . "/$groupCode";

            $id = 'expansion.admin_groups.config.label.' . $groupCode;
            $container->setDefinition($id, new ChildDefinition('expansion.admin_groups.config.label.abstract'))
                ->replaceArgument('$path', "$pathPrefix/label")
                ->replaceArgument('$defaultValue', $group['label']);
            $configManager->addMethodCall('registerConfig', [new Reference($id), $id]);

            $id = 'expansion.admin_groups.config.logins.' . $groupCode;
            $container->setDefinition($id, new ChildDefinition('expansion.admin_groups.config.logins.abstract'))
                ->setArgument('$path', "$pathPrefix/logins")
                ->setArgument('$defaultValue', $group['logins']);
            $configManager->addMethodCall('registerConfig', [new Reference($id), $id]);

            if ($groupCode != "master_admin") {
                foreach ($permissions as $permission) {
                    $id = 'expansion.admin_groups.config.permissions.' . $groupCode . ".$permission";
                    $container->setDefinition($id, new ChildDefinition('expansion.admin_groups.config.permissions.abstract'))
                        ->setArgument('$path', "$pathPrefix/perm_$permission")
                        ->setArgument('$defaultValue', in_array($permission, $group['permissions']))
                        ->setArgument('$name', "expansion_admin_groups.permission.$permission.label")
                        ->setArgument('$description', "expansion_admin_groups.permission.$permission.description");
                    $configManager->addMethodCall('registerConfig', [new Reference($id), $id]);
                }
            }
        }
    }
}
