<?php


namespace eXpansion\Framework\Core\Model\Gui\Factory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use FML\Controls\Frame;
use FML\ManiaLink;
use FML\Script\Features\ToggleInterface;
use FML\Types\Container;


/**
 * Class WidgetFrameFactory
 *
 * @package eXpansion\Framework\Core\Model\Gui\Factory;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WidgetFrameFactory implements WidgetFrameFactoryInterface
{

    /**
     * Build the window frame content.
     *
     * @param Widget          $manialink
     * @param Frame|Container $mainFrame to build into
     * @param                 $name
     * @param float           $sizeX     Size of the inner frame to build the window frame around
     * @param float           $sizeY     Size of the inner frame to build the window frame around
     *
     * @return void
     */
    public function build(Widget $manialink, Frame $mainFrame, $name, $sizeX, $sizeY)
    {
        $toggleInterfaceF9 = new ToggleInterface($mainFrame, "F9");
        $manialink
            ->getFmlManialink()
            ->getScript()
            ->addFeature($toggleInterfaceF9);
    }
}
