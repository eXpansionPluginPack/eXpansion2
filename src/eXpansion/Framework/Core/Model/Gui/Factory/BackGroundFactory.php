<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use FML\Controls\Quads\Quad_BgsPlayerCard;

/**
 * Class LineBuilder
 *
 * @package eXpansion\Framework\Core\Model\Gui\Builders;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class BackGroundFactory
{
    public function create($width, $height, $index = 0)
    {
        $index = $index % 2;

        // TODO set proper default size & font.
        $background = new Quad_BgsPlayerCard(); //BgRacePlayerName
        $background->setSubStyle(Quad_BgsPlayerCard::SUBSTYLE_BgActivePlayerName)
            ->setSize($width, $height);

        return $background;
    }
}