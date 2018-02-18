<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Script\Features\ScriptFeature;
use FML\Types\Renderable;

abstract class AbstractUiElement extends ScriptFeature implements Renderable
{
    protected $_classes = [];
    protected $_dataAttributes = [];
    /**
     * @var int
     */
    protected $posX = 0;
    /**
     * @var int
     */
    protected $posY = 0;
    /**
     * @var int
     */
    protected $posZ = 0;

    /**
     * @var int
     */
    protected $width = 1;
    /**
     * @var int
     */
    protected $height = 1;
    /**
     * @var string
     */
    protected $horizontalAlign = "left";
    /**
     * @var string
     */
    protected $verticalAlign = "top";

    /**
     * @param int $posX
     * @param int $posY
     * @param int $posZ
     * @return AbstractUiElement
     */
    public function setPosition($posX = 0, $posY = 0, $posZ = 0)
    {
        $this->posX = $posX;
        $this->posY = $posY;
        $this->posZ = $posZ;

        return $this;

    }

    /**
     * @param $X
     * @return $this
     */
    public function setX($X)
    {
        $this->posX = $X;

        return $this;
    }

    /**
     * @param $Y
     * @return $this
     */
    public function setY($Y)
    {
        $this->posY = $Y;

        return $this;
    }

    /**
     * @param $Z
     * @return $this
     */
    public function setZ($Z)
    {
        $this->posZ = $Z;

        return $this;
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    abstract public function render(\DOMDocument $domDocument);

    /**
     * @return int
     */
    public function getX()
    {
        return $this->posX;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->posY;
    }

    /**
     * @return int
     */
    public function getZ()
    {
        return $this->posZ;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return static
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param mixed $height
     * @return static
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param $x
     * @param $y
     * @return $this
     */
    public function setSize($x, $y)
    {
        $this->width = $x;
        $this->height = $y;

        return $this;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function addDataAttribute($name, $value)
    {
        $this->_dataAttributes[$name] = $value;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function addClass($name)
    {
        $this->_classes[] = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getHorizontalAlign(): string
    {
        return $this->horizontalAlign;
    }

    /**
     * @param string $horizontalAlign
     * @return static
     */
    public function setHorizontalAlign(string $horizontalAlign)
    {
        $this->horizontalAlign = $horizontalAlign;

        return $this;
    }

    /**
     * @return string
     */
    public function getVerticalAlign(): string
    {
        return $this->verticalAlign;
    }

    /**
     * @param string $verticalAlign
     * @return static
     */
    public function setVerticalAlign(string $verticalAlign)
    {
        $this->verticalAlign = $verticalAlign;

        return $this;
    }

}
