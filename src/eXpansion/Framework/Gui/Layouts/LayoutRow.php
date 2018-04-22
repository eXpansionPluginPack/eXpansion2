<?php

namespace eXpansion\Framework\Gui\Layouts;

use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Elements\Format;
use FML\Script\Features\ScriptFeature;
use FML\Types\Container;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class LayoutRow implements Renderable, ScriptFeatureable, Container
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
    protected $margin = 0;

    /**
     * @var float|int
     */
    protected $posX;

    /**
     * @var float|int
     */
    protected $posy;

    /**
     * @var float|int
     */
    protected $startY = 0;

    /** @var float */
    protected $additionalHeight;

    protected $hAlign = "left";
    protected $vAlign = "top";


    /**
     * layoutLine constructor.
     *
     * @param float    $posX
     * @param float    $posY
     * @param object[] $elements
     * @param int      $margin
     *
     * @throws \Exception
     */
    public function __construct($posX, $posY, $elements = [], $margin = 1, $additionalHeight = 0)
    {
        if (!is_array($elements)) {
            throw new \Exception('not an array');
        }

        $this->margin = $margin;
        $this->elements = $elements;
        $this->additionalHeight = $additionalHeight;
        $this->setPosition($posX, $posY);
        $this->updateSize();
    }

    /**
     * Update the size of the layout according to all the elements in it.
     */
    protected function updateSize()
    {
        $sizeY = abs($this->startY);
        $sizeX = 0;
        foreach ($this->elements as $idx => $element) {
            $sizeY += $element->getHeight() + $this->margin;

            if (abs($element->getX()) + $element->getWidth() > $sizeX) {
                $sizeX = abs($element->getX()) + $element->getWidth();
            }
        }

        $this->setSize($sizeX, $sizeY);
    }

    /**
     * Set position.
     *
     * @param double $x
     * @param double $y
     * @return LayoutRow
     */
    public function setPosition($x, $y)
    {
        $this->posX = $x;
        $this->posy = $y;

        return $this;
    }

    /**
     * @param mixed $posX
     *
     * @return LayoutRow
     */
    public function setX($posX)
    {
        $this->posX = $posX;

        return $this;
    }

    /**
     * @param mixed $posY
     *
     * @return LayoutRow
     */
    public function setY($posY)
    {
        $this->posy = $posY;

        return $this;
    }

    /**
     * @param float|int $startY
     */
    public function setStartY($startY)
    {
        $this->startY = $startY;
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
        $frame->setPosition($this->posX, $this->posy);
        $frame->addClasses($this->frameClasses);
        $frame->setSize($this->getWidth(), $this->getHeight()+4);
        
        $startY = $this->startY;

        foreach ($this->elements as $idx => $element) {
            $element->setY($startY);
            $startY -= $element->getHeight() + $this->margin;
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
        return $this->posX;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->posy;
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
     * @return LayoutRow
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


    /**
     * @param string $class
     * @return LayoutRow
     */
    public function addClass($class)
    {
        $this->frameClasses[] = $class;

        return $this;
    }

    /**
     * Add a new child
     *
     * @api
     * @param Renderable $child Child Control to add
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
     *
     * @param Renderable[] $children Child Controls to add
     *
     */
    public function addChildren(array $children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * Remove all children
     *
     * @api
     *
     */
    public function removeAllChildren()
    {
        $this->width = 0;
        $this->height = 0;
        $this->elements = [];

        return $this;
    }

    /**
     * Remove all children
     *
     * @api
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
     * @deprecated Use Style
     * @see        Style
     */
    public function setFormat(Format $format = null)
    {

    }

    /**
     * @param float $sizeX
     * @param float $sizeY
     * @return LayoutRow
     */
    private function setSize($sizeX, $sizeY)
    {
        $this->width = $sizeX;
        $this->height = $sizeY;

        return $this;

    }

    /**
     * @param string $hAling
     * @param string $vAlign
     * @return LayoutRow
     */
    public function setAlign($hAling = "left", $vAlign = "top")
    {
        $this->hAlign = $hAling;
        $this->vAlign = $vAlign;

        return $this;
    }

    public function getHorizontalAlign()
    {
        return $this->hAlign;
    }

    public function getVerticalAlign()
    {
        return $this->vAlign;
    }
}
