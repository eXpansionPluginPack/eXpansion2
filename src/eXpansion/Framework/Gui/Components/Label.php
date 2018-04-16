<?php

namespace eXpansion\Framework\Gui\Components;

use DOMDocument;
use DOMElement;
use FML\Controls\Label as FMLLabel;

class Label extends FMLLabel
{

    const TYPE_NORMAL = "normal";
    const TYPE_TITLE = "title";
    const TYPE_HEADER = "header";

    /**
     * Label constructor.
     * @param string      $text
     * @param string      $type
     * @param string|null $controlId
     */
    public function __construct($text = "", $type = self::TYPE_NORMAL, $controlId = null)
    {
        parent::__construct($controlId);
        $this->setText($text)
            ->setAreaColor("0000")
            ->setAreaFocusColor('0000')
            ->setScriptEvents(true)
            ->setTextColor('fff')
            ->setWidth(30);

        switch ($type) {
            case self::TYPE_NORMAL:
                $this->setTextSize(1)
                    ->setHeight(3)
                    ->setTextFont('BiryaniDemiBold');
                break;
            case self::TYPE_TITLE:
                $this->setTextSize(1)
                    ->setHeight(3)
                    ->setTextFont('RajdhaniMono');

                break;
            case self::TYPE_HEADER:
                $this->setTextSize(1)
                    ->setHeight(3)
                    ->setTextFont('BiryaniDemiBold');
                break;
            default:
                $this->setTextSize(1)
                    ->setHeight(3);
                break;
        }
    }

    /**
     * @param DOMDocument $domDocument
     * @return DOMElement
     */

    public function render(\DOMDocument $domDocument)
    {
        if ($this->getTranslate() === true) {
            if ($this->getText()) {
                $this->setText(null);
            }
        }

        return parent::render($domDocument);
    }

    /**
     * @param boolean $translate
     * @return Label
     */
    public function setTranslate($translate)
    {
        if ($translate) {
            $text = $this->getText();
            if ($text) {
                parent::setText(null);
                parent::setTextId($text);
            }
        }

        parent::setTranslate($translate);

        return $this;
    }

    public function setTextId($textId)
    {
        $this->setText($textId);
        $this->setTranslate(true);

        return $this;
    }

}
