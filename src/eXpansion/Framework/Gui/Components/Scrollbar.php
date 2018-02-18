<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Script\Script;
use FML\Types\Renderable;

class Scrollbar extends AbstractUiElement implements Renderable
{

    const AXIS_X = "X";
    const AXIS_Y = "Y";

    /**
     * @var string
     */
    private $axis;
    /**
     * @var float
     */
    private $length;
    /**
     * @var float
     */
    private $scrollbarSize;

    /**
     * uiScrollbar constructor.
     * @param string $axis
     * @param float  $posX
     * @param float  $posY
     * @param        $scrollbarSize
     * @param float  $length
     */
    public function __construct($axis, $posX, $posY, $scrollbarSize, $length)
    {

        $this->axis = $axis;
        $this->setPosition($posX, $posY);
        $this->length = $length;
        if ($this->axis == "X") {
            $this->setWidth($length);
            $this->setHeight(5.);
        } else {
            $this->setHeight($length);
            $this->setWidth(5.);
        }
        $this->scrollbarSize = $scrollbarSize;
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        if ($this->axis == self::AXIS_X) {
            $frame = $this->getScrollbarX();
        } else {
            $frame = $this->getScrollbarY();
        }

        return $frame->render($domDocument);
    }

    private function getScrollbarX()
    {
        $frame = new Frame();
        $frame->setPosition($this->posX, $this->posY)
            ->addClass('uiScrollbarControl');

        $scrollbar = new Quad();
        $scrollbar->setPosition(5, 0)
            ->setSize($this->scrollbarSize, 5)
            ->setBackgroundColor('fffa')
            ->setFocusBackgroundColor("ffff")
            ->addClass('uiScrollbar')
            ->addDataAttribute('axis', $this->axis)
            ->setAlign('left', 'bottom')
            ->setScriptEvents(true);

        $leftLabel = new Label();
        $leftLabel->setPosition(0, 0)
            ->setSize(5, 5)
            ->setText("⏴")
            ->setScriptEvents(true)
            ->setAreaColor('aaa')
            ->setAreaFocusColor('777')
            ->setAlign("left", "bottom");

        $rightLabel = new Label();
        $rightLabel->setPosition($this->length, 0)
            ->setSize(5, 5)
            ->setText("⏵")
            ->setScriptEvents(true)
            ->setAreaColor('aaa')
            ->setAreaFocusColor('777')
            ->setAlign("right", "bottom");

        $background = new Quad();
        $background->setSize($this->length, 5)
            ->setAlign("left", "bottom")
            ->setBackgroundColor("000a");

        $frame->addChild($scrollbar);
        $frame->addChild($leftLabel);
        $frame->addChild($rightLabel);
        $frame->addChild($background);

        return $frame;

    }

    private function getScrollbarY()
    {
        $frame = new Frame();
        $frame->setPosition($this->posX, $this->posY)
            ->addClass('uiScrollbarControl');


        $scrollbar = new Quad();
        $scrollbar->setPosition(0, -5)
            ->setSize(5, $this->scrollbarSize)
            ->setBackgroundColor('fffa')
            ->setFocusBackgroundColor('ffff')
            ->addClass('uiScrollbar')
            ->addDataAttribute('axis', $this->axis)
            ->setAlign('right', 'top')
            ->setScriptEvents(true);

        $topLabel = new Label();
        $topLabel->setPosition(0, 0)
            ->setSize(5, 5)
            ->setText("⏶")
            ->setScriptEvents(true)
            ->setAreaColor('aaa')
            ->setAreaFocusColor('777')
            ->setAlign("right", "top");

        $bottomLabel = new Label();
        $bottomLabel->setPosition(0, -$this->length)
            ->setSize(5, 5)
            ->setText("⏷")
            ->setScriptEvents(true)
            ->setAreaColor('aaa')
            ->setAreaFocusColor('777')
            ->setAlign("right", "bottom");

        $background = new Quad();
        $background->setSize(5, $this->length)
            ->setAlign("right", "top")
            ->setBackgroundColor("000a");

        $frame->addChild($scrollbar);
        $frame->addChild($topLabel);
        $frame->addChild($bottomLabel);
        $frame->addChild($background);

        return $frame;
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


}
