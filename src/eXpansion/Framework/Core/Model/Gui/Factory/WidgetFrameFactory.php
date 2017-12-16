<?php


namespace eXpansion\Framework\Core\Model\Gui\Factory;

use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Widget;
use FML\Controls\Frame;
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

    /** @var ManiaScriptFactory */
    protected $widgetManiaScriptFactory;

    /**
     * WindowFrameFactory constructor.
     *
     * @param ManiaScriptFactory $maniaScriptFactory
     */
    public function __construct(ManiaScriptFactory $maniaScriptFactory)
    {
        $this->widgetManiaScriptFactory = $maniaScriptFactory;
    }

    /**
     * Build the window frame content.
     *
     * @param Widget          $manialink
     * @param Frame|Container $mainFrame to build into
     * @param string          $name
     * @param float           $sizeX     Size of the inner frame to build the window frame around
     * @param float           $sizeY     Size of the inner frame to build the window frame around
     * @param boolean         $hideable
     *
     * @return void
     */
    public function build(Widget $manialink, Frame $mainFrame, $name, $sizeX, $sizeY, $hideable)
    {
        if ($hideable) {
            $toggleInterfaceF9 = new ToggleInterface($mainFrame, "F9");

            $manialink
                ->getFmlManialink()
                ->getScript()
                ->addFeature($toggleInterfaceF9);
        }
        $manialink->getFmlManialink()->addChild($this->widgetManiaScriptFactory->createScript(['']));

    }
}
