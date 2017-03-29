<?php

namespace eXpansion\Core\Model\Gui;

use eXpansion\Core\Exceptions\Gui\MissingCloseActionException;
use eXpansion\Core\Model\UserGroups\Group;
use Manialib\Manialink\Elements\Frame;
use Manialib\Manialink\Elements\Label;
use Manialib\Manialink\Elements\Quad;
use Manialib\Manialink\Elements\Script;
use Manialib\XML\Rendering\Renderer;

class Window extends Manialink
{
    protected $manialink;

    protected $closeButton;

    public function __construct(
        Group $group,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($group, $name, $sizeX, $sizeY, $posX, $posY);

        $titleHeight = 5.5;
        $closeButtonWidth = 9.5;
        $closeButtonHighliteColor = "D30707ee";
        $titlebarColor = "3afe";

        $ml = new \Manialib\Manialink\Elements\Manialink();
        $ml->setVersion(3);
        $ml->setAttribute('id', $this->getId());
        $ml->setName($name);
        $window = Frame::create()->setId("Window")->setPosn($posX, $posY)->appendTo($ml);

        // titlebar text
        Label::create()
            ->setPosn(3, -$titleHeight / 2)
            ->setAlign("left", "center2")
            ->setText($name)
            ->setTextcolor("fff")
            ->setTextsize(2)
            ->setAttribute("textfont", "RajdhaniMono")
            ->appendTo($window);


        // titlebar
        Quad::create()
            ->setSizen($sizeX, 0.33)
            ->setPosn(0, -$titleHeight)
            ->setBgcolor("fff")
            ->appendTo($window);

        Quad::create()
            ->setSizen($sizeX / 4, 0.5)
            ->setAlign("left", "bottom")
            ->setPosn(0, -$titleHeight)
            ->setBgcolor("fff")
            ->appendTo($window);

        Quad::create()
            ->setSizen($sizeX - $closeButtonWidth, $titleHeight)
            ->setBgcolor($titlebarColor)
            ->setId("Title")
            ->setScriptevents()
            ->appendTo($window);

        $this->closeButton = Label::create()
            ->setId("Close")
            ->setSizen($closeButtonWidth, $titleHeight)
            ->setPosn($sizeX - $closeButtonWidth + ($closeButtonWidth / 2), -$titleHeight / 2)
            ->setAlign("center", "center2")
            ->setText("✖")
            ->setScriptevents()
            ->setTextcolor("fff")
            ->setFocusareacolor1($titlebarColor)
            ->setFocusareacolor2($closeButtonHighliteColor)
            ->setTextSize(2)
            ->setAttribute("textfont", "OswaldMono")
            ->appendTo($window);

        //body
        Quad::create()
            ->setSizen($sizeX, ($sizeY - $titleHeight))
            ->setPosn(0, -$titleHeight)
            ->setStyle("Bgs1:BgWindow3")
            ->appendTo($window);

        Quad::create()
            ->setStyle("Bgs1InRace:BgButtonShadow")
            ->setSizen($sizeX + 10, $sizeY + 10)
            ->setPosn(-5, 5)
            ->appendTo($window);


        $script = /** @lang textmate */
            <<<'EOD'

#Include "AnimLib" as AL

main () {

    declare CMlFrame Window <=> (Page.GetFirstChild("Window") as CMlFrame);
    declare CMlQuad Titlebar <=> (Page.GetFirstChild("Title") as CMlQuad);
    declare CMlLabel CloseButton <=> (Page.GetFirstChild("Close") as CMlLabel);
    declare moveWindow = False;
    declare closeWindow = False;
    declare openWindow = False;
    declare Vec2 Offset = <0.0, 0.0	>;
    declare Real zIndex = 0.;
    declare Boolean MoveWindow = False;
    declare Integer lastAction = Now;
	
    while(True) {
		yield;
		
        if (openWindow) {
            AnimMgr.Add(Window, "<frame scale=\"1.\" />", 200, CAnimManager::EAnimManagerEasing::SineIn);   
       	}
        
        if (closeWindow) {     	 
         	AnimMgr.Add(Window, "<frame scale=\"0.\" />", 200, CAnimManager::EAnimManagerEasing::SineOut);   
        }

        if (openWindow && lastAction + 200 <= Now ) {
            openWindow = False;
            lastAction = 0;
        }

	    if (closeWindow && lastAction + 200 <= Now ) {
            closeWindow = False;
            TriggerPageAction(CloseButton.DataAttributeGet("action"));
	        continue;
	    }
	
	    if (MoveWindow) {
            Window.RelativePosition_V3.X = MouseX + Offset.X;
	        Window.RelativePosition_V3.Y = MouseY + Offset.Y;
	    }
	
	    if (PendingEvents.count != 0) {
            foreach (Event in PendingEvents) {
                if ( (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "Close")  || 
                   (Event.Type == CMlEvent::Type::KeyPress && Event.KeyCode == 35) ) {
                    closeWindow = True;
                    lastAction = Now;                  
                }
                
                if ( (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "Open") ) {
                    lastAction = Now;
                    openWindow = True;
                }
            }
	    }	
	
	    if (MouseLeftButton == True) {
            foreach (Event in PendingEvents) {
                if (Event.Type == CMlEvent::Type::MouseClick && Event.ControlId == "Title")  {		
                    Offset = <Window.RelativePosition_V3.X - MouseX, Window.RelativePosition_V3.Y - MouseY>;
                    MoveWindow = True;
                }
            }
        } else {
            MoveWindow = False;
        }

    }

}
EOD;
        Script::create()->setNodeValue($script)->appendTo($ml);
        $this->manialink = $ml;
    }

    public function setCloseAction($actionId)
    {
        $this->closeButton->setAttribute('data-action', $actionId);
    }

    public function getXml()
    {
        if (empty($this->closeButton->getAttribute('data-action')))
        {
            throw new MissingCloseActionException("Close action is missing for window. Check if you are using the proper factory.");
        }

        $renderer = new Renderer();
        return $renderer->getXML($this->manialink);
    }

}
