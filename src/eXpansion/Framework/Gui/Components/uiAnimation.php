<?php

namespace eXpansion\Framework\Gui\Components;

use FML\Controls\Control;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Script\Features\ScriptFeature;
use FML\Script\Script;
use FML\Script\ScriptInclude;
use FML\Types\Container;
use FML\Types\ScriptFeatureable;

class uiAnimation extends abstractUiElement implements ScriptFeatureable
{
    const Linear = "Linear";

    const QuadIn = "QuadIn";
    const QuadOut = "QuadOut";
    const QuadInOut = "QuadInOut";

    const CubicIn = "CubicIn";
    const CubicOut = "CubicOut";
    const CubicInOut = "CubicInOut";

    const QuartIn = "QuartIn";
    const QuartOut = "QuartOut";
    const QuartInOut = "QuartInOut";

    const QuintIn = "QuintIn";
    const QuintOut = "QuintOut";
    const QuintInOut = "QuintInOut";

    const SineIn = "SineIn";
    const SineOut = "SineOut";
    const SineInOut = "SineInOut";

    const ExpIn = "ExpIn";
    const ExpOut = "ExpOut";
    const ExpInOut = "ExpInOut";

    const CircIn = "CircIn";
    const CircOut = "CircOut";
    const CircInOut = "CircInOut";

    const BackIn = "BackIn";
    const BackOut = "BackOut";
    const BackInOut = "BackInOut";

    const ElasticIn = "ElasticIn";
    const ElasticOut = "ElasticOut";
    const ElasticInOut = "ElasticInOut";

    const Elastic2In = "Elastic2In";
    const Elastic2Out = "Elastic2Out";
    const Elastic2InOut = "Elastic2InOut";

    const BounceIn = "BounceIn";
    const BounceOut = "BounceOut";
    const BounceInOut = "BounceInOut";

    protected $element;

    /**
     * uiAnimation constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param $control
     * @param $animations
     * @param $duration
     * @param $delay
     * @param $easing
     * @internal param $text
     */
    public function addAnimation($control, $animations, $duration, $delay, $easing)
    {
        if ($control instanceof Control) {
            $control->addClass("uiAnimation");
            $control->addDataAttribute("animate", $animations);
            $control->addDataAttribute("duration", $duration);
            $control->addDataAttribute("delay", $delay);
            $control->addDataAttribute("easing", $easing);
        }
    }

    /**
     * Prepare the given Script for rendering by adding the needed Labels, etc.
     *
     * @param Script $script Script to prepare
     * @return static
     */
    public function prepare(Script $script)
    {
        $script->addScriptFunction("exp_AnimationFunctions", $this->getFunctions());

        return $this;
    }


    public function getFunctions()
    {

        return /** @lang textmate */
            <<<EOL
            CAnimManager::EAnimManagerEasing convertEasing(Text text) {
                switch (text) {
                    case "QuadIn":  {
                                    return  CAnimManager::EAnimManagerEasing::QuadIn;
                    }
                    case "QuadOut":  {
                                    return  CAnimManager::EAnimManagerEasing::QuadOut;
                    }		
                    case "QuadInOut": {
                                    return  CAnimManager::EAnimManagerEasing::QuadInOut;
                    }
                    case "CubicIn": {
                                    return  CAnimManager::EAnimManagerEasing::CubicIn;
                    }
                    case "CubicOut": {
                                    return  CAnimManager::EAnimManagerEasing::CubicOut;
                    }		
                    case "CubicInOut": {
                                    return  CAnimManager::EAnimManagerEasing::CubicInOut;
                    }
                    case "QuartIn":  {
                                    return  CAnimManager::EAnimManagerEasing::QuartIn;
                    }
                    case "QuartOut":  {
                                    return  CAnimManager::EAnimManagerEasing::QuartOut;
                    }		
                    case "QuartInOut":  {
                                    return  CAnimManager::EAnimManagerEasing::QuartInOut;
                    }
                    case "QuintIn":  {
                                    return  CAnimManager::EAnimManagerEasing::QuintIn;
                    }
                    case "QuintOut":  {
                                    return  CAnimManager::EAnimManagerEasing::QuintOut;
                    }		
                    case "QuintInOut":  {
                                    return  CAnimManager::EAnimManagerEasing::QuintInOut;
                    }
                    case "QuintIn":  {
                                    return  CAnimManager::EAnimManagerEasing::QuintIn;
                    }
                    case "QuintOut":  {
                                    return  CAnimManager::EAnimManagerEasing::QuintOut;
                    }		
                    case "QuintInOut":  {
                                    return  CAnimManager::EAnimManagerEasing::QuintInOut;
                    }	
                    case "SineIn":  {
                                    return  CAnimManager::EAnimManagerEasing::SineIn;
                    }
                    case "SineOut":  {
                                    return  CAnimManager::EAnimManagerEasing::SineOut;
                    }		
                    case "SineInOut":  {
                                    return  CAnimManager::EAnimManagerEasing::SineInOut;
                    }			 
                    case "ExpIn":  {
                                    return  CAnimManager::EAnimManagerEasing::ExpIn;
                    }
                    case "ExpOut":  {
                                    return  CAnimManager::EAnimManagerEasing::ExpOut;
                    }		
                    case "ExpInOut":  {
                                    return  CAnimManager::EAnimManagerEasing::ExpInOut;
                    }		            
                    case "CircIn":  {
                                    return  CAnimManager::EAnimManagerEasing::CircIn;
                    }
                    case "CircOut":  {
                                    return  CAnimManager::EAnimManagerEasing::CircOut;
                    }		
                    case "CircInOut":  {
                                    return  CAnimManager::EAnimManagerEasing::CircInOut;
                    }		
                    case "BackOut": {
                                    return  CAnimManager::EAnimManagerEasing::BackOut;
                    }
                    case "BackIn":  {
                                    return  CAnimManager::EAnimManagerEasing::BackIn;
                    }		
                    case "BackInOut":  {
                                    return  CAnimManager::EAnimManagerEasing::BackInOut;
                    }		
                    case "ElasticIn": {
                                    return  CAnimManager::EAnimManagerEasing::ElasticIn;
                    }
                    case "ElasticOut": {
                                    return  CAnimManager::EAnimManagerEasing::ElasticOut;
                    }
                    case "ElasticInOut": {
                                    return  CAnimManager::EAnimManagerEasing::ElasticInOut;
                    }
                    case "ElasticIn2": {
                                    return  CAnimManager::EAnimManagerEasing::ElasticIn2;
                    }
                    case "ElasticOut2": {
                                    return  CAnimManager::EAnimManagerEasing::ElasticOut2;
                    }
                    case "ElasticInOut2": {
                                    return  CAnimManager::EAnimManagerEasing::ElasticInOut2;
                    }		
                    case "BounceIn": {
                                    return  CAnimManager::EAnimManagerEasing::BounceIn;
                    }
                    case "BounceOut": {
                                    return  CAnimManager::EAnimManagerEasing::BounceOut;
                    }
                    case "BounceInOut": {
                                    return  CAnimManager::EAnimManagerEasing::BounceInOut;
                    }
                    
                }
                return CAnimManager::EAnimManagerEasing::Linear;
            }

       ***FML_OnInit***
       ***
            declare Boolean doAnimation = True;
	
	        if (doAnimation) {
                Page.GetClassChildren ("uiAnimation", Page.MainFrame, True);
                foreach (control in Page.GetClassChildren_Result) {
                    declare Text xmlString = "<elem " ^ TextLib::ReplaceChars(control.DataAttributeGet("animate"), "'", "\"") ^ "/>";
                    declare Integer duration = TextLib::ToInteger(control.DataAttributeGet("duration"));
                    declare Integer delay = TextLib::ToInteger(control.DataAttributeGet("delay"));
                    declare Text easing = control.DataAttributeGet("easing");
                    AnimMgr.Add(control, xmlString, Now + delay, duration, convertEasing(easing));	
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
        $quad = new Quad();
        $quad->setVisible(false);

        return $quad->render($domDocument);
    }
}
