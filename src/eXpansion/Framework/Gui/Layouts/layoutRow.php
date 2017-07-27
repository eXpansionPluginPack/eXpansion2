<?php

namespace eXpansion\Framework\Gui\Layouts;

use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Script\Features\ScriptFeature;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class layoutRow implements Renderable, ScriptFeatureable
{
    private $frameClasses = [];
    /**
     * @var float|int
     */
    private $height = 0;
    /**
     * @var float|int
     */
    private $width = 0;

    /** @var Control[] */
    private $elements = [];

    /**
     * @var float|int
     */
    private $margin = 1;

    /**
     * @var float|int
     */
    private $startX;

    /**
     * @var float|int
     */
    private $startY;

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
        $this->startX = $startX;
        $this->startY = $startY;

        foreach ($this->elements as $idx => $element) {
            $this->width += $element->getWidth();
            $this->height += $element->getHeight() + $this->margin;
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
        $frame->setPosition($this->startX, $this->startY);
        $frame->addClasses($this->frameClasses);

        $startY = 0;
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
     * @param mixed $startX
     * @return static
     */
    public function setX($startX)
    {
        $this->startX = $startX;

        return $this;
    }

    /**
     * @param mixed $startY
     * @return static
     */
    public function setY($startY)
    {
        $this->startY = $startY;

        return $this;
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
     * @param object $element
     */
    public function addChild($element)
    {
        $this->elements[] = $element;
        $this->width += $element->getWidth();
        $this->height += $element->getHeight() + $this->margin;
    }

    public function addClass($class)
    {
        $this->frameClasses[] = $class;
    }
}
