<?php

namespace eXpansion\Framework\Gui\Layouts;

use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Elements\Format;
use FML\Script\Features\ScriptFeature;
use FML\Types\Container;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class layoutRow implements Renderable, ScriptFeatureable, Container
{

    protected $frameClasses = [];
    /**
     * @var float|int
     */
    protected $height = 0;
    /**
     * @var float|int
     */
    protected $width = 0;

    /** @var Control[] */
    protected $elements = [];

    /**
     * @var float|int
     */
    protected $margin = 1;

    /**
     * @var float|int
     */
    protected $startX;

    /**
     * @var float|int
     */
    protected $startY;

    protected $hAlign = "left";
    protected $vAlign = "top";


    /**
     * layoutLine constructor.
     * @param float $startX
     * @param float $startY
     * @param object[] $elements
     * @param int $margin
     * @throws \Exception
     */
    public function __construct($startX, $startY, $elements = [], $margin = 0)
    {
        if (!is_array($elements)) {
            throw new \Exception('not an array');
        }

        $this->margin = $margin;
        $this->elements = $elements;
        $this->setPosition($startX, $startY);
        $this->updateSize();
    }

    protected function updateSize()
    {
        $sizeX = 0;
        $sizeY = 0;
        foreach ($this->elements as $idx => $element) {
            $sizeY += $element->getY() + $element->getHeight() + $this->margin;

            if (abs($element->getX()) + $element->getWidth() > $sizeX) {
                $sizeX = abs($element->getX()) + $element->getWidth();
            }
        }
        $this->setSize($sizeX, $sizeY);
    }

    public function setPosition($x, $y)
    {
        $this->startX = $x;
        $this->startY = $y;
    }

    /**
     * @param mixed $startX
     * @return
     */
    public function setX($startX)
    {
        $this->startX = $startX;

        return $this;
    }

    /**
     * @param mixed $startY
     * @return
     */
    public function setY($startY)
    {
        $this->startY = $startY;

        return $this;
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
        $frame->setAlign($this->hAlign, $this->vAlign);
        $frame->setPosition($this->startX, $this->startY);
        $frame->addClasses($this->frameClasses);

        $startY = 0;

        foreach ($this->elements as $idx => $element) {
            $pos = $element->getY();
            $element->setY($startY);
            $startY -= $element->getHeight() - $this->margin;
            $frame->addChild($element);
        }

        return $frame->render($domDocument);
    }

    /**
     * Get the Script Features
     *
     * @return ScriptFeature[]
     */
    public function getScriptFeatures()
    {
        $features = [];
        foreach ($this->elements as $element) {
            if ($element instanceof ScriptFeatureable) {
                $features[] = $element->getScriptFeatures();
            }
        }

        return ScriptFeature::collect($features);
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->startX;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->startY;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @param float $width
     * @return layoutRow
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param Renderable $element
     */
    public function addChild(Renderable $element)
    {
        $this->elements[] = $element;
        $this->updateSize();
    }

    public function getChildren()
    {
        return $this->elements;
    }


    public function addClass($class)
    {
        $this->frameClasses[] = $class;
    }

    /**
     * Add a new child
     *
     * @api
     * @param Renderable $child Child Control to add
     * @return static
     * @deprecated Use addChild()
     * @see        Container::addChild()
     */
    public function add(Renderable $child)
    {
        // TODO: Implement add() method.
    }

    /**
     * Add new children
     *
     * @api
     * @param Renderable[] $children Child Controls to add
     * @return static
     */
    public function addChildren(array $children)
    {
        // TODO: Implement addChildren() method.
    }

    /**
     * Remove all children
     *
     * @api
     * @return static
     */
    public function removeAllChildren()
    {
        // TODO: Implement removeAllChildren() method.
    }

    /**
     * Remove all children
     *
     * @api
     * @return static
     * @deprecated Use removeAllChildren()
     * @see        Container::removeAllChildren()
     */
    public function removeChildren()
    {
        // TODO: Implement removeChildren() method.
    }

    /**
     * Get the Format
     *
     * @api
     * @param bool $createIfEmpty If the format should be created if it doesn't exist yet
     * @return Format
     * @deprecated Use Style
     * @see        Style
     */
    public function getFormat($createIfEmpty = true)
    {
        // TODO: Implement getFormat() method.
    }

    /**
     * Set the Format
     *
     * @api
     * @param Format $format New Format
     * @return static
     * @deprecated Use Style
     * @see        Style
     */
    public function setFormat(Format $format = null)
    {
        // TODO: Implement setFormat() method.
    }

    private function setSize($sizeX, $sizeY)
    {
        $this->width = $sizeX;
        $this->height = $sizeY;
    }

    public function setAlign($hAling = "left", $vAlign = "top")
    {
        $this->halign = $hAling;
        $this->valign = $vAlign;
    }


}
