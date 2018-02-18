<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Quad;
use FML\Script\Script;

class Line extends AbstractUiElement
{
    public $length = 0;

    /**
     * @var float
     */
    protected $x;
    /**
     * @var float
     */
    protected $y;
    /**
     * @var float
     */
    protected $tx;
    /**
     * @var float
     */
    protected $ty;

    protected $color = "fffa";

    protected $stoke = 0.25;

    protected $to = false;

    protected $rotate = 0;

    /**
     * Line constructor.
     * @param float $x
     * @param float $y
     */
    public function __construct($x, $y)
    {

        $this->x = $x + 180.;
        $this->y = $y + 90.;
    }

    public function to($tx, $ty)
    {
        $this->tx = $tx + 180.;
        $this->ty = $ty + 90.;
        $this->to = true;
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return void
     */
    public function prepare(Script $script)
    {
        // do nothing
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $quad = new Quad();
        $quad->setPosition($this->posX + $this->x - 180, $this->posY + $this->y - 90);
        $quad->setBackgroundColor($this->color);
        if ($this->to) {
            $quad->setWidth($this->calcLength())->setHeight($this->stoke);
            $quad->setRotation($this->calcAngle());
        } else {
            $quad->setWidth($this->length)->setHeight($this->stoke);
            $quad->setRotation($this->rotate);
        }

        return $quad->render($domDocument);
    }

    public function calcLength()
    {

        $vx = $this->tx - $this->x;
        $vy = $this->ty - $this->y;

        return sqrt(($vx * $vx) + ($vy * $vy));
    }

    public function calcAngle()
    {
        $angle = (float)(atan2($this->x - $this->tx, $this->y - $this->ty));
        $angle += pi() / 2.0;

        return rad2deg($angle);
    }

    /**
     * @return float
     */
    public function getStoke()
    {
        return $this->stoke;
    }

    /**
     * @param float $stoke
     * @return line
     */
    public function setStroke($stoke)
    {
        $this->stoke = $stoke;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return line
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return int
     */
    public function getRotate()
    {
        return $this->rotate;
    }

    /**
     * @param int $rotate
     * @return line
     */
    public function setRotate($rotate)
    {
        $this->rotate = $rotate;

        return $this;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     * @return line
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

}
