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

class uiInput extends abstractUiElement implements Renderable, ScriptFeatureable
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

    protected $textFormat = "Basic";

    public function __construct($name, $default = "", $width = 30, $textFormat = "Basic")
    {
        $this->name = $name;
        $this->default = $default;
        $this->width = $width;
        $this->setSize($width, 5);
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
            ->addClasses(["uiContainer", "uiInput"]);

        $quad = new Quad();
        $quad->setSize($this->width * 2, $this->height * 2)
            ->setScale(0.5)
            ->setPosition($this->width / 2, -$this->height / 2)
            ->setStyles('Bgs1', 'BgColorContour')
            ->setAlign("center", "center")
            ->setBackgroundColor('FFFA')
            ->addClass("uiInput")
            ->setScriptEvents(true)
            ->setDataAttributes($this->_dataAttributes)->addClasses($this->_classes);

        $input = new Entry();
        $input->setSize($this->width, $this->height)
            ->setPosition(0, -$this->height / 2)
            ->setDefault($this->default)
            ->setSelectText(true)
            ->setAlign("left", "center2")
            ->setAreaColor("0005")
            ->setAreaFocusColor('000a')
            ->setTextFormat($this->textFormat)
            ->setName($this->name)
            ->setTextSize(2);

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
             if (Event.Control.HasClass("uiInput") ) {
                 log("editing");
                 declare CMlFrame frame <=> Event.Control.Parent;
                 (frame.Controls[1] as CMlEntry).StartEdition();               
            }					
EOL;

    }


}
