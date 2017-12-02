<?php

namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\Window;
use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Types\Container;

interface WindowFrameFactoryInterface
{
    /**
     * Build the window frame content.
     *
     * @param Window $manialink
     * @param Frame|Container $mainFrame to build into
     * @param $name
     * @param float $sizeX Size of the inner frame to build the window frame around
     * @param float $sizeY Size of the inner frame to build the window frame around
     *
     * @return Control
     */
    public function build(Window $manialink, Frame $mainFrame, $name, $sizeX, $sizeY);
}
