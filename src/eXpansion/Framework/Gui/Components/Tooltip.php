<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Types\ScriptFeatureable;

class Tooltip extends AbstractUiElement implements ScriptFeatureable
{

    protected $element;

    /**
     * @param AbstractUiElement|Control $control
     * @param string                    $text
     */
    public function addTooltip($control, $text)
    {
        if ($control instanceof Control) {
            $control->addDataAttribute("tooltip", $text);
            $control->addClass("uiTooltip");
            $control->setScriptEvents(true);

            return;
        }

        if ($control instanceof AbstractUiElement) {
            $control->addDataAttribute("tooltip", $text);
            $control->addClass("uiTooltip");

            return;
        }
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return void
     *
     */
    public function prepare(Script $script)
    {
        $script->addScriptFunction("exp_tooltipFunctions", $this->getFunctions());
    }


    public function getFunctions()
    {

        return /** @lang textmate */
            <<<EOL
            
       ***FML_OnInit***
       ***
       declare CMlFrame exp_tooltip = (Page.GetFirstChild("exp_tooltip") as CMlFrame);	
	   declare Boolean exp_tooltip_move = False;
	   declare Boolean exp_tooltip_toggle = True;
	   declare Integer exp_tooltip_delay = 0;
	   declare Vec2 mouse_pos = <0., 0.>;
	   declare Vec2 exp_tooltip_rel = <0., 0.>;     
       ***
                    
       ***FML_Loop***
       ***
       if (exp_tooltip_move) {         
            exp_tooltip.RelativePosition_V3 =  <MouseX, MouseY> - mouse_pos + exp_tooltip_rel;
                             
          /*  if (exp_tooltip_rel.Y > -10.) {
                exp_tooltip.RelativePosition_V3 = exp_tooltip_rel + <4., 0.>;
            } else { 
               exp_tooltip.RelativePosition_V3 = exp_tooltip_rel + <4., 4.>;
            } */
            
            if (exp_tooltip_delay + 350 < Now) {
                if (exp_tooltip_toggle) {
                    AnimMgr.Add(exp_tooltip.Controls[0], "<elem scale=\"1\" />",  450, CAnimManager::EAnimManagerEasing::ElasticOut);
                    AnimMgr.Add(exp_tooltip.Controls[1], "<elem scale=\"1\" />",  450, CAnimManager::EAnimManagerEasing::ElasticOut);
                    exp_tooltip_toggle = False;
                }          	    					
            }								
	   }
	   
	   if (MouseLeftButton) {
	            (exp_tooltip.Controls[0] as CMlLabel).RelativeScale = 0.;
                (exp_tooltip.Controls[1] as CMlQuad).RelativeScale = 0.;       
	   }
       ***
       
       ***FML_MouseOver***      
       ***
       if (Event.Control != Null) {
			if (Event.Control.HasClass("uiTooltip") )  {
                declare tooltipLabel = (exp_tooltip.Controls[0] as CMlLabel);
                declare text = Event.Control.DataAttributeGet("tooltip");
                declare sizeX = tooltipLabel.ComputeWidth(text);			 			    						       
                tooltipLabel.Value = text;
                tooltipLabel.Size.X = sizeX;    
                (exp_tooltip.Controls[1] as CMlQuad).Size.X = sizeX;                            
                exp_tooltip_move = True;
                exp_tooltip_delay = Now;
                exp_tooltip_toggle = True;
                mouse_pos = <MouseX, MouseY>;
              //  exp_tooltip_rel = Event.Control.AbsolutePosition_V3 + Exp_Window.RelativePosition_V3;
                exp_tooltip_rel = Event.Control.AbsolutePosition_V3 - Exp_Window.RelativePosition_V3;                                                  
            }
        }
       ***
       
       ***FML_MouseOut***      
       ***
       if (Event.Control != Null) {
			if (Event.Control.HasClass("uiTooltip") )  {
                exp_tooltip_move = False;
                exp_tooltip_delay = 0;  
                exp_tooltip_toggle = True;                         
                (exp_tooltip.Controls[0] as CMlLabel).RelativeScale = 0.;
                (exp_tooltip.Controls[1] as CMlQuad).RelativeScale = 0.;          	                                	
            }    
       }
       ***
                      
EOL;

    }


    /**
     * Get the Script Features
     *
     * @return ScriptFeature[]
     */
    public function getScriptFeatures()
    {
        return ScriptFeature::collect($this);
    }

    /**
     * Render the XML element
     *
     * @param \DOMDocument $domDocument DOMDocument for which the XML element should be rendered
     * @return \DOMElement
     */
    public function render(\DOMDocument $domDocument)
    {
        $frame = new Frame("exp_tooltip");
        $frame->setZ(100)->setAlign("left", "center");

        $label = new Label();
        $label->setSize(36, 5)
            ->setAlign("left", "center2")
            ->setTextFont('file://Media/Font/BiryaniDemiBold.Font.gbx')
            ->setTextSize(2)
            ->setTextColor("eee")
            ->setOpacity(1)
            ->setAreaFocusColor("0000")
            ->setAreaColor("0000")
            ->setScriptEvents(true)
            ->setScale(0);

        $quad = new Quad();
        $quad->setAlign("left", "center2")->setScale(0);
        $quad->setSize(36, 5)->setBackgroundColor('000')->setOpacity(1)->setScriptEvents(true);


        $frame->addChild($label);
        $frame->addChild($quad);

        return $frame->render($domDocument);
    }
}
