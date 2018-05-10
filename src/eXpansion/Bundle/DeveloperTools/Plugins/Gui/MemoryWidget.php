<?php

namespace eXpansion\Bundle\DeveloperTools\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Label;

class MemoryWidget extends WidgetFactory
{
    /** @var  Label */
    protected $memoryMessage;

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $this->memoryMessage = Label::create();
        $this->memoryMessage->setTextPrefix('$s')->setTextSize(3)->setSize(60, 5)
            ->setScale(0.7);
        $manialink->addChild($this->memoryMessage);
    }

    /**
     * @param string $message
     */
    public function setMemoryMessage($message)
    {
        $this->memoryMessage->setText($message);
    }
}
