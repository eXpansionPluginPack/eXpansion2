<?php

namespace eXpansion\Framework\Gui\Layouts;

use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Elements\Format;
use FML\Script\Features\ScriptFeature;
use FML\Types\Container;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class layoutLine implements Renderable, ScriptFeatureable, Container
{
    protected $frameClasses = [];

    protected $frameId = null;

    /** @var float */
    protected $width = 0.;

    /** @var float */
    protected $height = 0.;

    /** @var Control[] */
    protected $elements = [];

    /** @var float */
    private $margin = 2.;
    /**
     * @var float
     */
    protected $startX = 0.;
    /**
     * @var float
     */
    protected $startY = 0.;

    /** @var string */
    protected $hAlign = "left";

    /** @var string */
    protected $vAlign = "top";

    /**
     * layoutLine constructor.
     * @param float    $startX
     * @param float    $startY
     * @param object[] $elements
     * @param float    $margin
     * @throws \Exception
     */
    public function __construct($startX, $startY, $elements = [], $margin = 0.)
    {
        if (!is_array($elements)) {
            throw new \Exception('not an array');
        }
        $this->margin = $margin;
        $this->elements = $elements;
        $this->startX = $startX;
        $this->startY = $startY;
        $sizeY = 0;
        foreach ($this->elements as $idx => $element) {
            $this->width += $element->getWidth() + $this->margin;
            if (($element->getY() + $element->getHeight()) > $sizeY) {
                $sizeY = $element->getY() + $element->getHeight();
                $this->setHeight($sizeY);
            }
        }

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
        $frame->setId($this->frameId);
        $frame->setAlign($this->hAlign, $this->vAlign);
        $frame->setPosition($this->startX, $this->startY);
        $frame->addClasses($this->frameClasses);
        $sizeY = 0;
        /** @var Control $oldElement */
        $oldElement = null;
        foreach ($this->elements as $idx => $element) {

            if ($idx === 0) {
                $start = $this->getRelativeStartPosition($element);
            } else {
                $start = $this->getStartPosition($oldElement) + $this->margin;
            }

            if ($oldElement) {

                if ($oldElement->getHorizontalAlign() == "center" && $element->getHorizontalAlign() == "center") {
                    $element->setX($start + $oldElement->getWidth() + ($element->getWidth() / 2));
                } elseif ($oldElement->getHorizontalAlign() == "left" && $element->getHorizontalAlign() == "center") {
                    $element->setX($start + $oldElement->getWidth() + ($element->getWidth() / 2));
                } elseif ($oldElement->getHorizontalAlign() == "center" && $element->getHorizontalAlign() == "left") {
                    $element->setX($start + $oldElement->getWidth());
                } elseif ($oldElement->getHorizontalAlign() == "center" && $element->getHorizontalAlign() == "right") {
                    $element->setX($start + $oldElement->getWidth() + $element->getWidth());
                } elseif ($oldElement->getHorizontalAlign() == "right" && $element->getHorizontalAlign() == "right") {
                    $element->setX($start + $element->getWidth());
                } elseif ($oldElement->getHorizontalAlign() == "right" && $element->getHorizontalAlign() == "center") {
                    $element->setX($start + ($element->getWidth() / 2));
                } elseif ($oldElement->getHorizontalAlign() == "left" && $element->getHorizontalAlign() == "right") {
                    $element->setX($start + $oldElement->getWidth() + $element->getWidth());
                } elseif ($oldElement->getHorizontalAlign() == "right" && $element->getHorizontalAlign() == "left") {
                    $element->setX($start);
                } else {
                    $element->setX($start + $oldElement->getWidth());
                }
            } else {
                $element->setX($start);
            }

            if (($element->getY() + $element->getHeight()) > $sizeY) {
                $sizeY = $element->getY() + $element->getHeight();
                $this->setHeight($element->getY() + $element->getHeight());
            }
            $frame->addChild($element);
            $oldElement = $element;
        }

        return $frame->render($domDocument);
    }

    /**
     * @param Control $element
     * @return float|int
     */
    private function getRelativeStartPosition($element)
    {
        if (is_null($element)) {
            return 0;
        }
        switch ($element->getHorizontalAlign()) {
            case "left":
                return 0;
            case "center":
                return 0.5 * $element->getWidth();
            case "right":
                return $element->getWidth();
            default:
                return 0;
        }
    }

    /**
     * @param Control $element
     * @return float|int
     */
    private function getRelativeWidth($element)
    {
        if (is_null($element)) {
            return 0;
        }
        switch ($element->getHorizontalAlign()) {
            case "right":
                return $element->getWidth();
            case "center":
                return $element->getWidth();
            case "left":
                return $element->getWidth();
            default :
                return $element->getWidth();
        }
    }

    /**
     * @param Control $element
     * @return float|int
     */
    private function getStartPosition($element)
    {
        if (is_null($element)) {
            return 0;
        }

        switch ($element->getHorizontalAlign()) {
            case "center":
                return $element->getX() - (0.5 * $element->getWidth());
            default:
                return $element->getX();
        }
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
     * @param mixed $startX
     * @return layoutLine
     */
    public function setX($startX)
    {
        $this->startX = $startX;

        return $this;
    }

    /**
     * @param mixed $startY
     * @return layoutLine
     */
    public function setY($startY)
    {
        $this->startY = $startY;

        return $this;
    }

    /**
     * @return float
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
     * @param Renderable $element
     */
    public function addChild(Renderable $element)
    {
        $this->elements[] = $element;
        $this->width += $element->getWidth() + $this->margin;
        if (($element->getY() + $element->getHeight()) > $this->getHeight()) {
            $this->setHeight($element->getY() + $element->getHeight());
        }
    }

    public function getChildren()
    {
        return $this->elements;
    }


    public function addClass($class)
    {
        $this->frameClasses [] = $class;
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
        // do nothing
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
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    /**
     * Remove all children
     *
     * @api
     * @return static
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
     * @return void
     * @deprecated Use removeAllChildren()
     * @see        Container::removeAllChildren()
     */
    public function removeChildren()
    {
        // do nothing
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

    public function setAlign($hAling = "left", $vAlign = "top")
    {
        $this->hAlign = $hAling;
        $this->vAlign = $vAlign;

        return $this;
    }


    public function setPosition($x, $y)
    {
        $this->setX($x);
        $this->setY($y);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->frameId;
    }

    /**
     * @param null|string $frameId
     * @return layoutLine
     */
    public function setId($frameId)
    {
        $this->frameId = $frameId;

        return $this;
    }

    /**
     * @return string
     */
    public function getHorizontalAlign(): string
    {
        return $this->hAlign;
    }

    /**
     * @param string $hAlign
     * @return layoutLine
     */
    public function setHorizontalAlign(string $hAlign)
    {
        $this->hAlign = $hAlign;

        return $this;
    }

}
