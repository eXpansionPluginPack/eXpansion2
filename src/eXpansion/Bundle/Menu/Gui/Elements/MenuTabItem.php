<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 15.4.2018
 * Time: 19.49
 */

namespace eXpansion\Bundle\Menu\Gui\Elements;


use eXpansion\Framework\Gui\Components\AbstractUiElement;
use eXpansion\Framework\Gui\Components\Label;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Elements\Format;
use FML\Script\Script;
use FML\Types\Container;
use FML\Types\Renderable;

class MenuTabItem extends AbstractUiElement implements Container
{

    /** @var string */
    private $text;
    /**
     * @var bool
     */
    private $active = false;
    private $action;
    protected $translate = true;
    private $label;

    public function __construct($text, $action)
    {

        $this->text = $text;
        $this->action = $action;
        $this->label  = new Label($this->text, Label::TYPE_HEADER);
        $this->label->setPosition(0, 0);
        $this->label->setSize(24, 5);
        $this->label->setAction($this->action);
        $this->label->setTextSize(3);
        $this->label->setTextColor('FFFFFF');
        $this->label->setAreaColor("0000");

        $this->label->setAlign("center", "center2");
        $this->label->setTextId($this->text);


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
        $frame->setHorizontalAlign("center");
        $frame->setPosition($this->posX, $this->posY);


        $frame->addChild($this->label);

        if ($this->active) {
            $quad = Quad::create();
            $quad->setSize(24, 0.33);
            $quad->setBackgroundColor("fff");
            $quad->setAlign("center", "center2");
            $quad->setPosition(0, -3);
            $frame->addChild($quad);
        }

        return $frame->render($domDocument);
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return static
     */
    public function prepare(Script $script)
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return MenuTabItem
     */
    public function setActive(bool $active = true)
    {
        $this->active = $active;

        return $this;
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

        return $this;
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
        return $this;
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
        return $this;
    }

    /**
     * Remove all children
     *
     * @api
     * @return static
     */
    public function removeAllChildren()
    {
        return $this;
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
        return $this;
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

}