<?php

namespace eXpansion\Framework\AdminGroups;

use eXpansion\Framework\Core\eXpansionCore;
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
     * Gets the list of bundle dependencies.
     *
     * @return array
     */
    public function getBundleDependencies()
    {
        return [
            eXpansionCore::class
        ];
    }
}
