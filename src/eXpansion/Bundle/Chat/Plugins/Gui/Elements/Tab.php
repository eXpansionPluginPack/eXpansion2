<?php

namespace eXpansion\Bundle\Chat\Plugins\Gui\Elements;

use eXpansion\Framework\Gui\Components\abstractUiElement;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\Script;

class Tab extends abstractUiElement
{
    protected $rotation;
    protected $focusBackgroundColor = "f90";
    protected $backgroundColor = "000";
    protected $textColor = "fff";
    protected $translate = false;

    /** @var Quad */
    private $background;
    /** @var uiLabel */
    private $buttonLabel;
    /** @var float */
    protected $scale = 1.0;
    /** @var string */
    protected $action = null;
    /** @var string */
    protected $id;
    /** @var string */
    protected $text;

    public function __construct()
    {

        $this->setHorizontalAlign("center");

        $this->background = Quad::create();
        $this->buttonLabel = new uiLabel();
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return void
     */
    public function prepare(Script $script)
    {
        // void
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $elementFrame = Frame::create();
        $elementFrame->setAlign("center", "center")
            ->setRotation($this->rotation)
            ->setPosition($this->posX, $this->posY, $this->posZ)
            ->addClasses(['uiContainer', 'uiTab'])
            ->addDataAttribute("action", $this->action)
            ->setScale($this->scale)
            ->setId($this->getId()."_Frame");


        $this->buttonLabel->setSize($this->width, $this->height)
            ->setText($this->getText())
            ->setTextSize(1)
            ->setTextColor($this->textColor)
            ->addClass('uiTabLabel')
            ->setAlign("center", "center2")
            ->setScriptEvents(false);

        if ($this->translate) {
            $this->buttonLabel->setTextId($this->getText());
        }

        $elementFrame->addChild($this->buttonLabel);

        $this->background
            ->setBackgroundColor($this->backgroundColor)
            ->setFocusBackgroundColor($this->focusBackgroundColor)
            ->setSize($this->width, $this->height)
            ->setAlign("center", "center2")
            ->addClass("uiTabBackground")
            ->setOpacity(0.7)
            ->setId($this->getId())
            ->setScriptEvents(true);
        $elementFrame->addChild($this->background);


        $this->background->setDataAttributes($this->_dataAttributes);
        $this->background->addClasses($this->_classes);

        return $elementFrame->render($domDocument);
    }


    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Tab
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return float
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param float $scale
     * @return Tab
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Tab
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return bool
     */
    public function getTranslate()
    {
        return $this->translate;
    }

    /**
     * @param bool $translate
     * @return Tab
     */
    public function setTranslate($translate)
    {
        $this->translate = $translate;

        return $this;
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
     * @return Tab
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTextColor()
    {
        return $this->textColor;
    }

    /**
     * @param mixed $textColor
     * @return mixed
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param mixed $backgroundColor
     * @return Tab
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFocusBackgroundColor()
    {
        return $this->focusBackgroundColor;
    }

    /**
     * @param mixed $focusBackgroundColor
     * @return Tab
     */
    public function setFocusBackgroundColor($focusBackgroundColor)
    {
        $this->focusBackgroundColor = $focusBackgroundColor;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * @param mixed $rotation
     */
    public function setRotation($rotation)
    {
        $this->rotation = $rotation;

        return $this;
    }


}
