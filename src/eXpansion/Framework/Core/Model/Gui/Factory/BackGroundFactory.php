<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use FML\Controls\Quad;
use FML\Controls\Quads\Quad_BgsPlayerCard;

/**
 * Class LineBuilder
 *
 * @package eXpansion\Framework\Core\Model\Gui\Builders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class BackGroundFactory
{
    /**
     * @param     $width
     * @param     $height
     * @param int $index
     *
     * @return Quad
     */
    public function create($width, $height, $index = 0)
    {
        $index = $index % 2;

        $background = Quad::create();
        $background->setSize($width, $height);
        if ($index == 0) {
            $background->setBackgroundColor("eee5");
        } else {
            $background->setBackgroundColor("eee3");
        }
        return $background;
    }
}

