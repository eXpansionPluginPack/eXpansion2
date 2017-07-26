<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory as BaseWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiCheckbox;
use eXpansion\Framework\Gui\Components\uiDropdown;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Components\uiLine;
use eXpansion\Framework\Gui\Components\uiTooltip;
use eXpansion\Framework\Gui\Layouts\layoutLine;
use eXpansion\Framework\Gui\Layouts\layoutRow;
use FML\Controls\Label;
use FML\Controls\Quad;

class MemoryWidgetFactory extends WidgetFactory
{

    protected $memoryMessage = "";

    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $this->memoryMessage = new Label();
        $this->memoryMessage->setTextPrefix('$s')->setText("waiting data...");

        $manialink->getContentFrame()->setScale(0.8)->setPosition(160, -130);
        $manialink->addChild($this->memoryMessage);

    }

    public function setMemory($msg)
    {
        $this->memoryMessage->setText($msg);
    }

}
