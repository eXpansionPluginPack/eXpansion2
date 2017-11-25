<?php

namespace eXpansion\Framework\Gui\Builders;

use FML\Controls\Quad;
use FML\Types\Renderable;

class WidgetBackground implements Renderable
{
    protected $posX = 0;
    protected $posY = 0;
    protected $posZ = 0;
    protected $id;
    protected $width;
    protected $height;
    protected $action;

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
            ->setOpacity(0.4)->setBackgroundColor("000");
        if ($this->id) {
            $quad->setId($this->id);
        }
        if ($this->action) {
            $quad->setAction($this->action)->setFocusBackgroundColor("999");
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
     */
    public function setPosX(int $posX)
    {
        $this->posX = $posX;
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
     */
    public function setPosY(int $posY)
    {
        $this->posY = $posY;
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
     */
    public function setPosZ(int $posZ)
    {
        $this->posZ = $posZ;
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
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param mixed $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}
