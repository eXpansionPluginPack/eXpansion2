<?php

namespace eXpansion\Bundle\Acme\Plugins\Gui;

use eXpansion\Bundle\Acme\Plugins\Test;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;

use FML\Controls\Label;
use FML\Script\Script;
use FML\Script\ScriptLabel;

class MemoryWidgetFactory extends WidgetFactory
{
    /** @var  Label */
    protected $memoryMessage;

    public static $exp_hash;

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {

        $this->memoryMessage = new Label();
        $this->memoryMessage->setTextPrefix('$s')->setText("waiting data...");
        $manialink->addChild($this->memoryMessage);

        self::$exp_hash = uniqid("exp");
    }

    protected function updateContent(ManialinkInterface $manialink)
    {

        $this->memoryMessage->setText(Test::$memoryMsg);
        $now = uniqid("exp2");

        $message = "Time now:".date("h:i:s");


        $script = new Script();

        $hash = self::$exp_hash;

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
