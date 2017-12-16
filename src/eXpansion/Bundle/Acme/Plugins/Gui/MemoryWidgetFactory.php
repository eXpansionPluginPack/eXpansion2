<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Bundle\Acme\Plugins\Test;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Label;
use FML\Script\Script;
use FML\Script\ScriptLabel;

class MemoryWidgetFactory extends WidgetFactory
{
    /** @var  Label */
    protected $memoryMessage;

    /** @var Factory */
    protected $uiFactory;

    public static $exp_hash;

    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        Factory $uiFactory
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->uiFactory = $uiFactory;
    }

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {

        $this->memoryMessage = $this->uiFactory->createLabel("awaiting data...");
        $this->memoryMessage->setTextPrefix('$s')->setTextSize(3)->setSize(60, 5)
            ->setScale(0.7);
        $manialink->addChild($this->memoryMessage);

        self::$exp_hash = uniqid("exp");
    }

    protected function updateContent(ManialinkInterface $manialink)
    {

        $this->memoryMessage->setText(Test::$memoryMsg);
        $now = uniqid("exp2");

        $message = "Time now:".date("h:i:s");

        $hash = self::$exp_hash;

        $script = new Script();
        $script->addCustomScriptLabel(ScriptLabel::OnInit,
            '
           declare Text '.$hash.' for LocalUser = Text;
           '.$hash.' = "'.$message.'";
           declare Text '.$hash.'_check for LocalUser = "";
           '.$hash.'_check = "$now";
           '
        );
        $manialink->getFmlManialink()->setScript($script);
    }

}
