<?php

namespace eXpansion\Bundle\LocalRecords;

use eXpansion\Framework\Core\eXpansionCore;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use SymfonyBundles\BundleDependency\BundleDependency;
use SymfonyBundles\BundleDependency\BundleDependencyInterface;

/**
 * Class LocalRecordsBundle
 *
 * @package eXpansion\Bundle\LocalRecords;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class LocalRecordsBundle extends Bundle implements BundleDependencyInterface
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