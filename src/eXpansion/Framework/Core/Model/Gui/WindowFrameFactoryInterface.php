<?php
/**
 * Created by PhpStorm.
 * User: Käyttäjä
 * Date: 14.11.2017
 * Time: 2:38
 */

namespace eXpansion\Framework\Core\Model\Gui;

use FML\Controls\Control;
use FML\ManiaLink;
use FML\Controls\Frame;
use FML\Types\Container;

interface WindowFrameFactoryInterface
{
    /**
     * Build the window frame content.
     *
     * @param ManiaLink $manialink
     * @param Frame|Container $mainFrame to build into
     * @param $name
     * @param float $sizeX Size of the inner frame to build the window frame around
     * @param float $sizeY Size of the inner frame to build the window frame around
     *
     * @return Control
     */
    public function build(Manialink $manialink, Frame $mainFrame, $name, $sizeX, $sizeY);

    /**
     * @param ManialinkInterface $manialinkInterface
     */
    public function setManialinkInterface(ManialinkInterface $manialinkInterface);
}
