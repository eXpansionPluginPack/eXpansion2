<?php

namespace eXpansion\Framework\AdminGroups;

use eXpansion\Framework\AdminGroups\DependencyInjection\Compiler\ConfigPass;
use eXpansion\Framework\Config\eXpansionConfig;
use eXpansion\Framework\Core\eXpansionCore;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use /** @noinspection PhpUndefinedClassInspection */
    Symfony\Component\HttpKernel\Bundle\Bundle;
use SymfonyBundles\BundleDependency\BundleDependency;
use SymfonyBundles\BundleDependency\BundleDependencyInterface;

/** @noinspection PhpUndefinedClassInspection */

/**
 * Class EmotesBundle
 *
 * @package eXpansion\Bundle\Emotes
 */
class eXpansionAdminGroupsBundle extends Bundle implements BundleDependencyInterface
{
    use BundleDependency;

    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ConfigPass());
    }

    /**
     * Gets the list of bundle dependencies.
     *
     * @return array
     */
    public function getBundleDependencies()
    {
        return [
            eXpansionCore::class,
            eXpansionConfig::class,
        ];
    }
}
