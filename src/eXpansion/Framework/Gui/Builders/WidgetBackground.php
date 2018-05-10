<?php

namespace eXpansion\Framework\Gui\Builders;

use FML\Controls\Quad;
use FML\Types\Renderable;

/**
 * Class WidgetBackground
 * @package eXpansion\Framework\Gui\Builders
 */
class WidgetBackground implements Renderable
{
    /**
     * @var float
     */
    protected $posX = 0;
    /**
     * @var float
     */
    protected $posY = 0;
    /**
     * @var float
     */
    protected $posZ = 0;
    /**
     * @var string
     */
    protected $id;
    /**
     * @var float
     */
    protected $width;
    /**
     * @var float
     */
    protected $height;
    /**
     * @var string
     */
    protected $action;
    /** @var bool
     */
    private $blur;

    /**
     * WidgetBackground constructor.
     * @param float $width
     * @param float $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $quad = Quad::create();
        $quad->setPosition($this->posX, $this->posY)->setZ($this->posZ)
            ->setOpacity(0.4)->setBackgroundColor("000")
            ->setSize($this->width, $this->height);
        if ($this->id) {
            $quad->setId($this->id);
        }
        if ($this->action) {
            $quad->setAction($this->action)->setFocusBackgroundColor("999");
        }

        if ($this->blur) {
            $quad->setStyles("Bgs1", "BgDialogBlur");
        }

        return $quad->render($domDocument);
    }

    /**
     * @return int
     */
    public function getPosX(): int
    {
        return $this->posX;
    }

    /**
     * @param int $posX
     * @return WidgetBackground
     */
    public function setPosX(int $posX)
    {
        $this->posX = $posX;

        return $this;
    }

    /**
     * @param int $posX
     * @param int $posY
     * @return WidgetBackground
     */
    public function setPosition(int $posX, int $posY)
    {
        $this->posX = $posX;
        $this->posY = $posY;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosY(): int
    {
        return $this->posY;
    }

    /**
     * @param int $posY
     * @return WidgetBackground
     */
    public function setPosY(int $posY)
    {
        $this->posY = $posY;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosZ(): int
    {
        return $this->posZ;
    }

    /**
     * @param int $posZ
     * @return WidgetBackground
     */
    public function setPosZ(int $posZ)
    {
        $this->posZ = $posZ;

        return $this;
    }

    /**
     * @return double
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return WidgetBackground
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return double
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     * @return WidgetBackground
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     * @return WidgetBackground
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return WidgetBackground
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setBlur($blur = true)
    {
        $this->blur = $blur;

        return $this;

    }

}
