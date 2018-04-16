<?php

namespace eXpansion\Framework\Gui\Layouts;

use eXpansion\Framework\Gui\Components\AbstractUiElement;
use eXpansion\Framework\Gui\Components\Scrollbar;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Elements\Format;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Script\ScriptLabel;
use FML\Types\Container;
use FML\Types\Renderable;
use FML\Types\ScriptFeatureable;

class LayoutScrollable extends AbstractUiElement implements Renderable, ScriptFeatureable, Container
{

    protected $force = false;
    protected $_X = 0;
    protected $_Y = 0;
    protected $offset = 0;
    protected $scrollbarH = true;
    protected $scrollbarV = true;
    protected $parentFrame = null;
    protected $frame_posX = 0;
    protected $frame_posY = 0;

    /**
     * layoutScrollable constructor.
     * @param Container $frame
     * @param           $sizeX
     * @param           $sizeY
     */
    public function __construct(Container $frame, $sizeX, $sizeY)
    {
        $this->parentFrame = $frame;
        $this->frame_posX = $frame->getX();
        $this->frame_posY = $frame->getY();
        $this->setSize($sizeX, $sizeY);

        $frame->setPosition(0, 0);
    }

    /**
     * Set the axis which supports scrolling
     * default all axis enabled
     * @param bool $x
     * @param bool $y
     *
     * @return $this
     */
    public function setAxis($x, $y)
    {
        $this->scrollbarH = $x;
        $this->scrollbarV = $y;

        return $this;
    }

    /**
     * Forces container size.
     * @param $x
     * @param $y
     */
    public function forceContainerSize($x, $y)
    {
        $this->force = true;
        $this->_X = $x;
        $this->_Y = $y;
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $container = new Frame();
        $container->setPosition($this->frame_posX, $this->frame_posY);

        $quad = new Quad();
        // this quad is used to placeholder, if you need to add later "frame"  to the view.

        $contentFrame = new Frame();
        $contentFrame->addChild($this->parentFrame);

        $container->addChild($quad);
        $container->addChild($contentFrame);
        if ($this->scrollbarV) {
            $contentFrame->setSize($this->width - 5, $this->height);
            $this->offset = 5;
            $container->addChild(new Scrollbar(
                "Y",
                $this->getWidth(),
                0,
                10,
                $this->getHeight()
            ));
        }

        if ($this->scrollbarH) {
            $contentFrame->setSize($this->width - 5, $this->height - 5);
            $container->addChild(new Scrollbar(
                "X",
                0,
                -$this->getHeight(),
                10,
                $this->getWidth() - $this->offset
            ));
        }


        return $container->render($domDocument);
    }

    /**
     * Get the Script Features
     *
     * @return ScriptFeature[]
     */
    public function getScriptFeatures()
    {
        $features = [];
        if ($this->parentFrame instanceof ScriptFeatureable) {
            $features[] = $this->parentFrame->getScriptFeatures();
        }
        $features[] = $this;

        return ScriptFeature::collect($features);
    }


    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return void
     */
    public function prepare(Script $script)
    {
        $script->addCustomScriptLabel(ScriptLabel::MouseClick, $this->getScriptMouseClick());
        $script->addCustomScriptLabel(ScriptLabel::OnInit, $this->getScriptInit());
        $script->addCustomScriptLabel(ScriptLabel::Loop, $this->getScriptLoop());
    }

    protected function getScriptInit()
    {
        $offset = number_format($this->offset, 1, ".", "");

        return /** @lang textmate */
            <<<EOL
            
            declare CMlFrame exp_scroll_frame = Null;
            declare CMlFrame exp_scroll_content = Null;
            declare Vec2 exp_scroll_content_size = <0.,0.>; 
            declare Boolean exp_scroll_activeY = False;
            declare Boolean exp_scroll_activeX = False;
            declare Vec2 exp_scroll_pos = <0.,0.>;
            declare Real exp_scroll_offset = $offset;
EOL;
    }


    protected function getScriptMouseClick()
    {
        return /** @lang textmate */
            <<<EOL
            
            if (Event.Control != Null && Event.Control.HasClass("uiScrollbar") )  {
                if (Event.Control.DataAttributeGet("axis") == "X") {
                    exp_scroll_activeX = True;
                } else {
                    exp_scroll_activeY = True;																
                }
                exp_scroll_frame = Event.Control.Parent;
                exp_scroll_pos = <MouseX, MouseY> - Event.Control.RelativePosition_V3 ;
                exp_scroll_content = (exp_scroll_frame.Parent.Controls[1] as CMlFrame); // gets the bounding frame				
                exp_scroll_content_size = exp_scroll_content.Controls[0].Size;             
            }
EOL;
    }


    protected function getScriptLoop()
    {
        return /** @lang textmate */
            <<<EOL

        if (exp_scroll_activeY) {		
					declare Real pos = (MouseY - exp_scroll_pos.Y) ;
					declare Real upperLimit = exp_scroll_frame.RelativePosition_V3.Y - exp_scroll_frame.RelativePosition_V3.Y - 5.;
					declare Real lowerLimit =  upperLimit - exp_scroll_frame.Controls[3].Size.Y + exp_scroll_frame.Controls[0].Size.Y + 10.;
					
					if (pos > upperLimit) {
						pos = upperLimit;
					}
															
					if (pos < lowerLimit)  {  
						pos = lowerLimit;
					}
					
				declare Real start = (upperLimit - pos);
				declare Real diff = MathLib::Abs(lowerLimit - upperLimit);
								
				exp_scroll_frame.Controls[0].RelativePosition_V3.Y = pos; // update scrollbar position												
				exp_scroll_content.Controls[0].RelativePosition_V3.Y = (start / diff) * (exp_scroll_content_size.Y - exp_scroll_frame.Parent.Controls[0].Size.Y + 10.);  //  gets the content frame
		}
		
		if (exp_scroll_activeX) {		
					declare Real pos = ( MouseX - exp_scroll_pos.X);
					declare Real leftLimit = 5.;
					declare Real rightLimit =  leftLimit + exp_scroll_frame.Controls[3].Size.X - exp_scroll_frame.Controls[0].Size.X -10.;
					
					if (pos < leftLimit) {
						pos = leftLimit;
					}
															
					if (pos > rightLimit)  {  
						pos = rightLimit;
					}
					
				declare Real start =  (leftLimit - pos);
				declare Real diff = MathLib::Abs(leftLimit + rightLimit);
								
				exp_scroll_frame.Controls[0].RelativePosition_V3.X = pos; // update scrollbar position												
				exp_scroll_content.Controls[0].RelativePosition_V3.X = (start / diff) * (exp_scroll_content_size.X + 10);  //  gets the content frame
		}
		
		
		
		if (MouseLeftButton == False)  {
			exp_scroll_activeX = False;
			exp_scroll_activeY = False;
		}

EOL;
    }

    /**
     * Get the children
     *
     * @api
     * @return Renderable[]
     */
    public function getChildren()
    {
        return $this->parentFrame->getChildren();
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
        $this->parentFrame->addChild($child);

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
        // do nothing
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
        $this->parentFrame->addChildren($children);

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
        $this->parentFrame->removeAllChildren();

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
        // do nothing
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
        // do nothing
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
        // do nothing
    }
}
