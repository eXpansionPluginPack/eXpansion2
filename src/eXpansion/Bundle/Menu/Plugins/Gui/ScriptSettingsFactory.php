<?php

namespace eXpansion\Bundle\Menu\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Label;
use FML\Controls\Quad;

class ScriptSettingsFactory extends WidgetFactory
{


    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $label = new Label("test");
        $manialink->addChild($label);
    }


}
