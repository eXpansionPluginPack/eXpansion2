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
class TitleBackGroundFactory extends BackGroundFactory
{
    public function create($width, $height, $index = 0)
    {
        $background = new Quad();
        $background->setBackgroundColor('fff')
            ->setPosition(0, -$height + 0.25)
            ->setSize($width, 0.25);

        return $background;
    }
}
