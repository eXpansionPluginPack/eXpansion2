<?php

namespace eXpansion\Bundle\DeveloperTools\Plugins\Gui;

use eXpansion\Bundle\Notifications\Plugins\Gui\NotificationUpdater;
use eXpansion\Framework\Core\Model\Gui\FmlManialinkFactoryContext;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\FmlManialinkFactory;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Ui\Factory;
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
        $this->memoryMessage = $this->uiFactory->createLabel("awaiting data...");
        $this->memoryMessage->setTextPrefix('$s')->setTextSize(3)->setSize(60, 5)
            ->setScale(0.7);
        $manialink->addChild($this->memoryMessage);
        $manialink->getFmlManialink()->setScript(null);
    }

    /**
     * @param string $message
     */
    public function setMemoryMessage($message)
    {
        $this->memoryMessage->setText($message);
    }
}
