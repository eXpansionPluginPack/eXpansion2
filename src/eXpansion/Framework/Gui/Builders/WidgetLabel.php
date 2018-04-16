<?php

namespace eXpansion\Framework\Gui\Builders;

use eXpansion\Framework\Gui\Components\Label;

/**
 * Class WidgetLabel
 * @package eXpansion\Framework\Gui\Builders
 */
class WidgetLabel extends Label
{

    public function __construct(string $text = "", string $type = self::TYPE_NORMAL, string $controlId = null)
    {
        parent::__construct($text, $type, $controlId);
        $this->setAreaColor("0004")
            ->setAreaFocusColor("0004")
            ->setTextSize(1)
            ->setTextFont("")
            ->setTextPrefix(" ")
            ->setAlign("left", "center2")
            ->setScriptEvents(true);
    }

}
