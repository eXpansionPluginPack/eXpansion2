<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Entry;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Controls\TextEdit;
use FML\Script\Script;
use FML\Types\Renderable;

class uiTextbox extends abstractUiElement implements Renderable
{

    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $default;

    /**
     * @var int
     */
    protected $lines;


    /**
     *
     *
     * @param $name
     * @param string $default
     * @param int $lines
     * @param int $width
     */
    public function __construct($name, $default = "", $lines = 1, $width = 30)
    {
        $this->name = $name;
        $this->default = $default;
        $this->lines = $lines;
        $this->setSize($width, ($lines * 5)+2);
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
        $frame->setPosition($this->posX, $this->posY, $this->posZ)
            ->setSize($this->width, $this->height)
            ->addClasses(["uiContainer", "uiTextbox"]);

        $quad = new Quad();
        $quad->setSize(($this->width * 2), ($this->height * 2))
            ->setScale(0.5)
            ->setPosition(0, 0)
            ->setStyles('Bgs1', 'BgColorContour')
            ->setAlign("left", "top")
            ->setBackgroundColor('FFFA');


        $input = new TextEdit();
        $input->setSize($this->width, $this->height-2)
            ->setPosition(1, -1)
            ->setDefault($this->default)
            ->setAlign("left", "top")
            ->addClass("uiInput")
            ->setAreaColor("0005")
            ->setAreaFocusColor('000a')
            ->setTextFormat('Basic')
            ->setName($this->name)
            ->setScriptEvents(true)
            ->addClasses($this->_classes)
            ->setDataAttributes($this->_dataAttributes);

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
     * @return static
     */
    public function prepare(Script $script)
    {
        // do nothing
    }

    /**
     * @return int
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param int $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;

        return $this;
    }
}
