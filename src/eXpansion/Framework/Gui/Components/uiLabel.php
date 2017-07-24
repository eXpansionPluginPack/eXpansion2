<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Label;

class uiLabel extends Label
{

    const TYPE_NORMAL = "normal";
    const TYPE_TITLE = "title";
    const TYPE_HEADER = "header";

    /**
     * uiLabel constructor.
     * @param string $text
     * @param string $type
     * @param string|null $controlId
     */
    public function __construct($text = "", $type = self::TYPE_NORMAL, $controlId = null)
    {
        parent::__construct($controlId);
        $this->setText($text)
            ->setAreaColor("0000")
            ->setAreaFocusColor('0000')
            ->setScriptEvents(true);

        switch ($type) {
            case self::TYPE_NORMAL:
                $this->setTextSize(2)
                    ->setStyle("TextInfo")
                    ->setHeight(5);
                break;
            case self::TYPE_TITLE:
                $this->setTextSize(3)
                    ->setHeight(5)
                    ->setTextFont('file://Media/Font/BiryaniDemiBold.Font.gbx');
                break;
            case self::TYPE_HEADER:
                $this->setTextSize(3)
                    ->setHeight(5)
                    ->setTextFont('file://Media/Font/BiryaniDemiBold.Font.gbx');
                break;
            default:
                $this->setTextSize(2)
                    ->setHeight(5);
                break;
        }
    }

    /**
     * @param DOMDocument $domDocument
     * @return DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $text = "";
        if ($this->getTranslate() === true) {
            $text = $this->getText();
            $this->setTextId($text);
            $this->setText(null);
        }

        $xml = parent::render($domDocument);

        if ($this->getTranslate() === true) {
            $this->setText($text);
        }

        return $xml;
    }
}
