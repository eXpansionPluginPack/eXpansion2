<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Entry;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Script\ScriptLabel;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class uiInputMasked extends abstractUiElement implements Renderable, ScriptFeatureable
{

    const TYPE_DEFAULT = "Basic";
    const TYPE_PASSWORD = "Password";

    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $default;

    protected $textFormat = "Password";

    public function __construct($name, $default = "", $width = 30, $textFormat = "Password")
    {
        $this->name = $name;
        $this->default = $default;
        $this->width = $width;
        $this->setSize($width, 4);
        $this->textFormat = $textFormat;
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $frame = new Frame();
        $frame->setPosition($this->posX, $this->posY)
            ->setSize($this->width, $this->height)
            ->addClasses(["uiContainer", "uiInputMasked"]);

        $quad = new Quad();
        $quad->setSize($this->width * 2, $this->height * 2)
            ->setScale(0.5)
            ->setPosition($this->width / 2, -$this->height / 2)
            ->setStyles('Bgs1', 'BgColorContour')
            ->setAlign("center", "center")
            ->setBackgroundColor('FFFA')
            ->addClass("uiInputMasked")
            ->setScriptEvents(true)
            ->setDataAttributes($this->_dataAttributes)->addClasses($this->_classes);

        $input = new Entry();
        $input->setSize($this->width - 4, $this->height)
            ->setPosition(0, -$this->height / 2)
            ->setDefault($this->default)
            ->setSelectText(true)
            ->setAlign("left", "center2")
            ->setAreaColor("0005")
            ->setAreaFocusColor('000a')
            ->setTextFormat($this->textFormat)
            ->addDataAttribute('type', 'Password')
            ->setName($this->name)
            ->setTextSize(1.5);

        $button = new uiButton("", uiButton::TYPE_DECORATED);
        $button->setSize(4, 4)
            ->addClass("uiMaskedToggle")
            ->setPosition($input->getWidth(), 0);

        $frame->addChild($button);
        $frame->addChild($quad);
        $frame->addChild($input);


        return $frame->render($domDocument);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param string $default
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight()
    {
        return $this->height + 2;
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     */
    public function prepare(Script $script)
    {
        $script->addCustomScriptLabel(ScriptLabel::MouseClick, $this->getScriptMouseClick());
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
     * @return string
     */
    public function getTextFormat()
    {
        return $this->textFormat;
    }

    /**
     * @param string $textFormat
     * @return $this
     */
    public function setTextFormat($textFormat)
    {
        $this->textFormat = $textFormat;

        return $this;
    }

    protected function getScriptMouseClick()
    {
        return <<<EOL
             if (Event.Control.HasClass("uiInputMasked") ) {              
                 declare CMlFrame frame <=> Event.Control.Parent;
                 (frame.Controls[2] as CMlEntry).StartEdition();               
            }	
             if (Event.Control.HasClass("uiMaskedToggle") ) {              
                 declare CMlFrame frame <=> Event.Control.Parent.Parent;
                 declare CMlEntry input <=> (frame.Controls[2] as CMlEntry);
                 if (input.DataAttributeGet("type") == "Basic") {
                   input.DataAttributeSet("type", "Password");
                   input.TextFormat = CMlEntry::ETextFormat::Password;
                 } else {
                   input.DataAttributeSet("type", "Basic");
                   input.TextFormat = CMlEntry::ETextFormat::Basic;
                 }               
            }	
            				
EOL;

    }
}
