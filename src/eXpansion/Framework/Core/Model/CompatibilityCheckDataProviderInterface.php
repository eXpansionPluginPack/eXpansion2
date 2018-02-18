<?php


namespace eXpansion\Framework\Core\Model;
use Maniaplanet\DedicatedServer\Structures\Map;


/**
 * Interface CompatibilityCheckDataProviderInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Model
 */
interface CompatibilityCheckDataProviderInterface
{

    /**
     * Check if data provider is compatible with current situation.
     *
     * @return bool
     */
    public function isCompatible(Map $map) : bool;
}
