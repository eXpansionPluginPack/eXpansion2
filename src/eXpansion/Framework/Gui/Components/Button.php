<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Elements\Format;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Script\ScriptLabel;
use FML\Types\Container;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class Button extends AbstractUiElement implements ScriptFeatureable, Container
{
    const TYPE_DECORATED = "decorated";
    const TYPE_DEFAULT = "default";
    const COLOR_DEFAULT = "777";
    const COLOR_SUCCESS = "0d0";
    const COLOR_WARNING = "d00";
    const COLOR_PRIMARY = "3af";
    const COLOR_SECONDARY = "000";

    /** @var  Label */
    protected $buttonLabel;
    protected $type;
    protected $textColor = "fff";
    protected $backColor = self::COLOR_DEFAULT;
    protected $focusColor = "aaa";
    protected $borderColor = "fff";
    protected $translate = true;

    protected $action = null;
    protected $text = "button";
    protected $scale = 1.;

    public function __construct($text = "button", $type = self::TYPE_DEFAULT)
    {
        $this->setHorizontalAlign("left");
        $this->setVerticalAlign("center");

        $this->text = $text;
        $this->type = $type;
        $this->setSize(18, 5);
        $this->buttonLabel = new Label("", Label::TYPE_TITLE);
        $this->buttonLabel->addClass('uiButtonElement');
        $this->buttonLabel->setTranslate($this->translate);

        $this->setHorizontalAlign("center");
        $this->setVerticalAlign("center");
        $this->setText($text);
    }


    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     *
     * <frame pos="64 -35" class="uiContainer UiButton">
     * <label size="26 9" data-color="fff" text="Cancel" class="button noAnim"  textprefix=" " opacity="1" halign="center" valign="center" focusareacolor1="0000" focusareacolor2="d00" scriptevents="1" translate="0" textsize="2"/>
     * <quad size="26 9" style="Bgs1" colorize="d00" substyle="BgColorContour" class="button" halign="center" valign="center" pos="0 0"/>
     * </frame>
     */
    public function render(\DOMDocument $domDocument)
    {
        $buttonFrame = new Frame();
        $buttonFrame->setAlign("center", "center")
            ->setPosition($this->posX + ($this->width / 2), $this->posY - ($this->height / 2), $this->posZ)
            ->addClasses(['uiContainer', 'uiButton'])
            ->addDataAttribute("action", $this->action)
            ->setScale($this->scale);

        foreach ($this->_dataAttributes as $name => $value) {
            $buttonFrame->addDataAttribute($name, $value);
        }

        if ($this->type == self::TYPE_DECORATED) {
            $quad = new Quad();
            $this->backColor = 0000;
            $quad->setStyles("Bgs1", "BgColorContour")
                ->setColorize($this->borderColor)
                ->setSize($this->width, $this->height)
                //->setPosition(-$this->width / 2, $this->height / 2)
                ->setAlign("center", "center2");
            $buttonFrame->addChild($quad);
        }

        $this->buttonLabel->setSize($this->width, $this->height)
            ->setTextSize(1)
            ->setScriptEvents(true)
            ->setAreaColor($this->backColor)
            ->setAreaFocusColor($this->focusColor)
            ->setTextColor($this->textColor)
            ->setAlign("center", "center2");

        if ($this->type != self::TYPE_DECORATED) {
            $this->buttonLabel->setAreaFocusColor($this->focusColor);
        }

        $this->buttonLabel->setDataAttributes($this->_dataAttributes);
        $this->buttonLabel->addClasses($this->_classes);

        $buttonFrame->addChild($this->buttonLabel);


        return $buttonFrame->render($domDocument);

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
     * @return Button
     */
    public function setText($text)
    {
        $this->text = $text;
        if ($this->translate) {
            $this->buttonLabel->setTextId($this->getText());
        } else {
            $this->buttonLabel->setText($this->getText());
        }

        return $this;
    }

    /**
     * Get the Script Features
     *
     * @return ScriptFeature[]
     */
    public function getScriptFeatures()
    {
        return ScriptFeature::collect($this);
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepar
     * @return void
     */
    public function prepare(Script $script)
    {
        $script->addScriptFunction("", $this->getScriptFunction());
        $script->addCustomScriptLabel(ScriptLabel::MouseClick, $this->getScriptMouseClick());
        $script->addCustomScriptLabel(ScriptLabel::MouseOver, <<<EOL
               if (Event.Control.Parent.HasClass("uiButton")) {                 
                  (Event.Control.Parent as CMlFrame).RelativeScale=1.1;
               }
EOL
        );

        $script->addCustomScriptLabel(ScriptLabel::MouseOut, <<<EOL
               if (Event.Control.Parent.HasClass("uiButton")) {                  
                  (Event.Control.Parent as CMlFrame).RelativeScale= 1.; 
               }
EOL
        );
    }

    protected function getScriptMouseClick()
    {
        return /** @lang textmate */
            <<<EOD
            if (Event.Control.HasClass("uiButtonElement") ) {            
                TriggerButtonClick(Event.Control);                             
            }
EOD;
    }

    protected function getScriptFunction()
    {
        return
            /** @lang textmate */
            <<<EOD
       
            Void TriggerButtonClick(CMlControl Control) {                
                 if (Control.Parent.HasClass("uiButton")) {
                    Control.Parent.RelativeScale = 0.75;
                    AnimMgr.Add(Control.Parent, "<elem scale=\"1.\" />", 200, CAnimManager::EAnimManagerEasing::QuadIn); 
                    TriggerPageAction(Control.Parent.DataAttributeGet("action"));
                 }                                
            }
            
            Void TriggerButtonClick(Text ControlId) {
                declare Control <=> Page.GetFirstChild(ControlId);
                TriggerButtonClick(Control);
            }


EOD;
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
     * @return Button
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
     * @return Button
     */
    public function setTextColor($textColor)
    {
        $this->textColor = $textColor;

        return $this;
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
     * @return Button
     */
    public function setBackgroundColor($backColor)
    {
        $this->backColor = $backColor;

        return $this;
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
     * @return Button
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;

        return $this;
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
     * @return Button
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
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
     * @return Button
     */
    public function setFocusColor($focusColor)
    {
        $this->focusColor = $focusColor;

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
     * @return Button
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

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
     * @return Button
     */
    public function setTranslate($translate = true)
    {
        $this->buttonLabel->setTranslate($translate);
        $this->setText($this->getText());

        return $this;
    }

    /**
     * Get the children
     *
     * @api
     * @return Renderable[]
     */
    public function getChildren()
    {
        return [$this->buttonLabel];
    }

    /**
     * Add a new child
     *
     * @api
     * @param Renderable $child Child Control to add
     * @deprecated
     * @return void
     */
    public function addChild(Renderable $child)
    {

    }

    /**
     * Add a new child
     *
     * @api
     * @param Renderable $child Child Control to add
     * @return void
     * @deprecated Use addChild()
     * @see        Container::addChild()
     */
    public function add(Renderable $child)
    {

    }

    /**
     * Add new children
     *
     * @api
     * @param Renderable[] $children Child Controls to add
     * @return void
     */
    public function addChildren(array $children)
    {

    }

    /**
     * Remove all children
     *
     * @api
     * @return void
     */
    public function removeAllChildren()
    {

    }

    /**
     * Remove all children
     *
     * @api
     * @return void
     * @deprecated Use removeAllChildren()
     * @see        Container::removeAllChildren()
     */
    public function removeChildren()
    {

    }

    /**
     * Get the Format
     *
     * @api
     * @param bool $createIfEmpty If the format should be created if it doesn't exist yet
     * @return void
     * @deprecated Use Style
     * @see        Style
     */
    public function getFormat($createIfEmpty = true)
    {

    }

    /**
     * Set the Format
     *
     * @api
     * @param Format $format New Format
     * @return void
     * @deprecated Use Style
     * @see        Style
     */
    public function setFormat(Format $format = null)
    {

    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->buttonLabel->getId();
    }

    /**
     * @param null $id
     * @return Button
     */
    public function setId($id)
    {
        $this->buttonLabel->setId($id);

        return $this;
    }
}
