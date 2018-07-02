<?php

namespace eXpansion\Framework\MlComposeBundle\Helpers;

/**
 * Class GridHelper
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\Helpers
 */
class GridHelper
{
    /**
     * Get normalized widths to fit a max width.
     *
     * @param float $totalWidth
     * @param float[] $widthCoefficiencies
     *
     * @return float[]
     */
    public function getNormalizedWidths($totalWidth, $widthCoefficiencies)
    {
        $totalCoefs = array_reduce(
            $widthCoefficiencies,
            function($total, $value) { return $total + $value; }
        );
        $multiplier = $totalWidth / $totalCoefs;

        array_walk($widthCoefficiencies, function (&$value, $key) use ($multiplier) {
            $value = $value * $multiplier;
        });
        return $widthCoefficiencies;
    }

}