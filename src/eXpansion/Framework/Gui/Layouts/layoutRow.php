<?php

namespace eXpansion\Framework\Gui\Layouts;

use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Script\Features\ScriptFeature;
use FML\Types\Container;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class layoutRow implements Renderable, ScriptFeatureable
{
    private $height = 0;
    private $width = 0;

    /** @var Control[] */
    private $elements = [];

    private $margin = 1;
    /**
     * @var
     */
    private $startX;
    /**
     * @var
     */
    private $startY;

    /**
     * layoutLine constructor.
     * @param $startX
     * @param $startY
     * @param Control[] $elements
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

    public function addElement($element)
    {
        $this->elements[] = $element;
        $this->width += $element->getWidth();
        $this->height += $element->getHeight() + $this->margin;
    }

}
