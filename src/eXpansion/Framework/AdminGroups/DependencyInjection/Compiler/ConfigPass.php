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
                    $id = 'expansion.admin_groups.config.permissions.' . $groupCode . '.permission';
                    $container->setDefinition($id, new ChildDefinition('expansion.admin_groups.config.logins.abstract'))
                        ->setArgument('$path', "$pathPrefix/perm_perm")
                        ->setArgument('$defaultValue', $group['logins'])
                        ->setArgument('$name', "expansion_admingroups.permission.$permission.label")
                        ->setArgument('$description', "expansion_admingroups.permission.$permission.description");
                    $configManager->addMethodCall('registerConfig', [new Reference($id), $id]);
                }
            }
        }
    }
}