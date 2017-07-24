<?php

namespace eXpansion\Framework\Gui\Components;

use eXpansion\Framework\Core\Helpers\ColorConversion;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\Script;
use FML\Types\Renderable;

class uiButton extends abstractUiElement
{
    private $type;

    private $textColor = "eee";
    private $backColor = self::COLOR_DEFAULT;
    private $focusColor = "bbb";
    private $borderColor = "fff";

    private $action = null;
    private $text = "button";

    const TYPE_DECORATED = "decorated";
    const TYPE_DEFAULT = "default";

    const COLOR_DEFAULT = "aaa";
    const COLOR_SUCCESS = "0d0";
    const COLOR_WARNING = "d00";
    const COLOR_PRIMARY = "3af";
    const COLOR_SECONDARY = "000";

    private $sizeX = 26;
    private $sizeY = 9;

    public function __construct($text = "button", $type = self::TYPE_DEFAULT)
    {
        $this->text = $text;
        $this->type = $type;
    }


    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     *
     * <frame pos="64 -35" class="uiContainer uiButton">
     * <label size="26 9" data-color="fff" text="Cancel" class="button noAnim"  textprefix=" " opacity="1" halign="center" valign="center" focusareacolor1="0000" focusareacolor2="d00" scriptevents="1" translate="0" textsize="2"/>
     * <quad size="26 9" style="Bgs1" colorize="d00" substyle="BgColorContour" class="button" halign="center" valign="center" pos="0 0"/>
     * </frame>
     */
    public function render(\DOMDocument $domDocument)
    {
        $buttonFrame = new Frame();
        $buttonFrame->setPosition($this->posX, $this->posY, $this->posZ)
            ->addClasses(['uiContainer', 'uiButton']);

        if ($this->type == self::TYPE_DECORATED) {
            echo "decorated!";
            //<quad size="26 9" style="Bgs1" colorize="d00" substyle="BgColorContour"
            $quad = new Quad();
            $this->backColor = null;
            $quad->setStyles("Bgs1", "BgColorContour")
                ->setColorize($this->borderColor)
                ->setSize($this->sizeX, $this->sizeY)
                ->setPosition($this->sizeX / 2, -$this->sizeY / 2)
                ->setAlign("center", "center2");
            $buttonFrame->addChild($quad);
        }

        $label = new uiLabel($this->getText(), uiLabel::TYPE_TITLE);
        $label->setSize($this->sizeX, $this->sizeY)
            ->setScriptEvents(true)
            ->setAreaColor($this->backColor)
            ->setAreaFocusColor($this->focusColor)
            ->setTextColor($this->textColor)
            ->setAlign("center", "center2")
            ->setPosition($this->sizeX / 2, -$this->sizeY / 2);

        if ($this->action !== null) {
            $label->setAction($this->action);
        }

        $buttonFrame->addChild($label);


        return $buttonFrame->render($domDocument);

    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return static
     *
     */
    public function prepare(Script $script)
    {
        // TODO: Implement prepare() method.
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param string $textColor
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;
    }

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backColor;
    }

    /**
     * @param string $backColor
     */
    public function setBackgroundColor($backColor)
    {
        $this->backColor = $backColor;
    }

    /**
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * @param string $borderColor
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;
    }

    /**
     * @return null
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param null $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function getFocusColor()
    {
        return $this->focusColor;
    }

    /**
     * @param string $focusColor
     */
    public function setFocusColor($focusColor)
    {
        $this->focusColor = $focusColor;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getSizeX()
    {
        return $this->sizeX;
    }

    /**
     * @param int $sizeX
     */
    public function setSizeX($sizeX)
    {
        $this->sizeX = $sizeX;
    }

    /**
     * @return int
     */
    public function getSizeY()
    {
        return $this->sizeY;
    }

    /**
     * @param int $sizeY
     */
    public function setSizeY($sizeY)
    {
        $this->sizeY = $sizeY;
    }

    /**
     * sets control size
     * @param int $x
     * @param int $y
     */
    public function setSize($x, $y)
    {
        $this->sizeX = $x;
        $this->sizeY = $y;
    }
}
