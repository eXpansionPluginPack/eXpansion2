<?php

namespace eXpansion\Framework\Gui\Builders;

use eXpansion\Framework\Gui\Components\Label;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Elements\Format;
use FML\Types\Container;
use FML\Types\Renderable;

/**
 * Class WidgetBackground
 * @package eXpansion\Framework\Gui\Builders
 */
class WidgetTitle implements Renderable, Container
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

    /** @var Label */
    private $label;

    /** @var Frame */
    private $frame;

    /**
     * WidgetBackground constructor.
     * @param string $text
     * @param float  $width
     * @param float  $height
     */
    public function __construct($text = "", $width, $height)
    {
        $this->frame = Frame::create();


        $this->label = Label::create();
        $this->setText($text);
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Render the XML element
     * <label pos="-14.5 2" z-index="10" size="21 4" text="Dedimania" halign="center" textsize="2" textcolor="fff" textfont="file://Media/Font/BiryaniDemiBold.Font.gbx" valign="center2" opacity="0.9"/>
     * <quad pos="-9 4" z-index="-4" size="31 4" halign="center" opacity="0.8" bgcolor="1783D0"/>
     * <quad pos="-25 4" z-index="-3.33" size="31 4" opacity="0.8" bgcolor="3af" halign="left"/>
     * <quad pos="6 2" z-index="-4" size="22 3.9" image="file://Media/Painter/Stencils/Scratch/Brush.tga" modulatecolor="1783D0" valign="center" keepratio="Clip" opacity="0.8"/>
     * <quad pos="6 2" z-index="-3" size="12 3.9" image="file://Media/Painter/Stencils/Scratch/Brush.tga" modulatecolor="3af" valign="center" keepratio="Clip" opacity="0.8"/>
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {

        $this->frame->setPosition($this->posX, $this->posY)->setZ($this->posZ);

        $w = $this->getWidth();
        $h = $this->getHeight();
        $opacity = 0.6;

        $this->label->setSize($this->getWidth() - 22, $this->getHeight())->setAlign("left", "center2")
            ->setPosition(1, -($h / 2))->setTextSize(2)->setTextColor("fff")->setTextFont("BiryaniDemiBold")
            ->setOpacity($opacity);

        $bg1 = Quad::create()
            ->setPosition(0, 0)
            ->setSize($w - 22,
                $h)->setOpacity($opacity)
            ->setBackgroundColor("1783D0");
        $bg2 = Quad::create()
            ->setPosition(0, 0)
            ->setSize($w - 22, $h)
            ->setOpacity($opacity)
            ->setBackgroundColor("3af");
        $tail = Quad::create()
            ->setPosition($w - 22, -($h / 2))
            ->setSize(22, ($h - 0.1))
            ->setOpacity($opacity)
            ->setModulizeColor("1783D0")
            ->setImageUrl("file://Media/Painter/Stencils/Scratch/Brush.tga")
            ->setKeepRatio("Clip")
            ->setAlign("left", "center");
        $tail2 = Quad::create()
            ->setPosition($w - 22, -($h / 2))
            ->setSize(12, ($h - 0.1))
            ->setOpacity($opacity)
            ->setModulizeColor("3af")
            ->setImageUrl("file://Media/Painter/Stencils/Scratch/Brush.tga")
            ->setKeepRatio("Clip")
            ->setAlign("left", "center");

        $this->frame->addChildren([
            $this->label,
            $bg1,
            $bg2,
            $tail,
            $tail2,
        ]);

        return $this->frame->render($domDocument);
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->posX;
    }

    /**
     * @param int $posX
     * @return WidgetTitle
     */
    public function setX(int $posX)
    {
        $this->posX = $posX;

        return $this;
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
     * @return WidgetTitle
     */
    public function setPosX(int $posX)
    {
        $this->posX = $posX;

        return $this;
    }

    /**
     * @param int $posX
     * @param int $posY
     * @return WidgetTitle
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
    public function getY(): int
    {
        return $this->posY;
    }

    /**
     * @param int $posY
     * @return WidgetTitle
     */
    public function setY(int $posY)
    {
        $this->posY = $posY;

        return $this;
    }

    /**
     * @return int
     */
    public function getZ(): int
    {
        return $this->posZ;
    }

    /**
     * @param int $posZ
     * @return WidgetTitle
     */
    public function setZ(int $posZ)
    {
        $this->posZ = $posZ;

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
     * @return WidgetTitle
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
     * @return WidgetTitle
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
     * @return WidgetTitle
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
     * @return WidgetTitle
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
     * @return WidgetTitle
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
     * @return WidgetTitle
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->label->setTextId($text);


        return $this;
    }

    /**
     * Get the children
     *
     * @api
     * @return Renderable[]
     */
    public function getChildren()
    {
        return [$this->label];
    }

    /**
     * Add a new child
     *
     * @api
     * @param Renderable $child Child Control to add
     * @return static
     */
    public function addChild(Renderable $child)
    {
        // TODO: Implement addChild() method.
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
}
