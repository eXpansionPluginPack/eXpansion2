<?php

namespace eXpansion\Framework\AdminGroups;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use SymfonyBundles\BundleDependency\BundleDependency;
use SymfonyBundles\BundleDependency\BundleDependencyInterface;

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
            \eXpansion\Framework\Core\eXpansionCore::class
        ];
    }
}
