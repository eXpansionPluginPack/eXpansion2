<?php

namespace eXpansion\Bundle\Chat\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class ChatWidgetFactory extends WidgetFactory
{

    /***
     * MenuFactory constructor.
     *
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param WidgetFactoryContext $context
     * @param VoteService $voteService
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);
        $posY = 0;
        $frm = Frame::create();
        $frm->setPosition(0, $posY);
        $btn = $this->uiFactory->createLabel();
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setText("")
            ->setScriptEvents(false);

        $bg = Quad::create();
        $bg->setOpacity(0.9)
            ->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setScriptEvents(true)
            ->setId("ButtonPublic")
            ->setBackgroundColor("000")
            ->setFocusBackgroundColor("f90");
        $tooltip->addTooltip($bg, "Public Chat");
        $frm->addChild($btn);
        $frm->addChild($bg);
        $manialink->addChild($frm);

        $posY -= 7;
        $frm = Frame::create();
        $frm->setPosition(0, $posY);
        $btn = $this->uiFactory->createLabel();
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setTextPrefix("")
            ->setScriptEvents(false);

        $bg = Quad::create();
        $bg->setOpacity(0.9)
            ->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setScriptEvents(true)
            ->setId("ButtonPrivate")
            ->setBackgroundColor("000")
            ->setFocusBackgroundColor("f90");
        $tooltip->addTooltip($bg, "Private Messages");
        $frm->addChild($btn);
        $frm->addChild($bg);
        $manialink->addChild($frm);


        $posY -= 7;
        $frm = Frame::create();
        $frm->setPosition(0, $posY);
        $btn = $this->uiFactory->createLabel();
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setTextPrefix("")
            ->setScriptEvents(false);

        $bg = Quad::create();
        $bg->setOpacity(0.9)
            ->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setScriptEvents(true)
            ->setId("ButtonServer")
            ->setBackgroundColor("000")
            ->setFocusBackgroundColor("f90");
        $tooltip->addTooltip($bg, "Console Messages");
        $frm->addChild($btn);
        $frm->addChild($bg);
        $manialink->addChild($frm);


        $privateFrame = Frame::create("PrivateMessages");
        $privateFrame->setVisible(false);
        $privateFrame->setPosition(6, -12);

        $nickline = $this->uiFactory->createLayoutLine(0, 0, [], 1);
        $privateFrame->addChild($nickline);

        for ($x = 1; $x <= 5; $x++) {
            $nick = $this->uiFactory->createLabel("NickName $x");
            $nick->setId("private_$x")
                ->setSize(26, 5)
                ->setScriptAction(true)
                ->setTextPrefix("  ")
                ->setAreaColor("000")
                ->setAreaFocusColor("f90")
                ->setOpacity(0.9)
                ->setAlign("left", "center2");
            $nickline->addChild($nick);
        }


        $privateLines = Frame::create("PrivateLines");
        $privateLines->setPosition(0, 0);
        $privateFrame->addChild($privateLines);

        $linerow = $this->uiFactory->createLayoutRow(2, -5, [], 1);

        for ($x = 0; $x < 7; $x++) {
            $line = new Label();
            $line->setStyle("TextRaceMessage")
                ->setId("line_$x")
                ->setSize(110, 4)
                ->setTextSize(1);
            $linerow->addChild($line);
        }

        $privateFrame->addChild($linerow);

        $manialink->addChild($privateFrame);


        $serverFrame = Frame::create("ServerMessages");
        $serverFrame->setVisible(false);
        $serverFrame->setPosition(6, -12);

        $serverLines = Frame::create("serverLines");
        $serverLines->setPosition(0, 0);
        $serverFrame->addChild($serverLines);

        $linerow = $this->uiFactory->createLayoutRow(2, -5, [], 1);

        for ($x = 0; $x < 8; $x++) {
            $line = new Label();
            $line->setStyle("TextRaceMessage")
                ->setId("serverline_$x")
                ->setSize(110, 4)
                ->setTextSize(1);
            $linerow->addChild($line);
        }

        $serverFrame->addChild($linerow);

        $manialink->addChild($serverFrame);


        $serverFrame = Frame::create("ServerMessages");
        $serverFrame->setVisible(false);
        $serverFrame->setPosition(6, -12);


        $manialink->addChild($serverFrame);

        $this->createManiascript($manialink);


    }

    private function createManiaScript(Widget $manialink)
    {

        $manialink->getFmlManialink()->getScript()->addScriptFunction("", <<<EOL
              
                Void Toggle(CMlQuad Button) {
                      declare CMlQuad ButtonServer <=> (Page.GetFirstChild("ButtonServer") as CMlQuad);
                      declare CMlQuad ButtonPrivate <=> (Page.GetFirstChild("ButtonPrivate") as CMlQuad);  
                      declare CMlQuad ButtonPublic <=> (Page.GetFirstChild("ButtonPublic") as CMlQuad);
                    
                      ButtonServer.BgColor = TextLib::ToColor("000");
                      ButtonPrivate.BgColor = TextLib::ToColor("000");
                      ButtonPublic.BgColor = TextLib::ToColor("000");
                      Button.BgColor = TextLib::ToColor("f90");                                              
                }
              
                Vec3 HsvToRgb(Vec3 _HSV) {
                    declare RGB = <0., 0., 0.>;
                    declare H = _HSV.X / 360;
                    declare S = _HSV.Y;
                    declare V = _HSV.Z;
                    
                    declare Hi = MathLib::FloorInteger(H * 6.);
                    declare F = (H * 6.) - Hi;
                    declare P = V * (1. - S);
                    declare Q = V * (1. - (F * S));
                    declare T = V * (1. - ((1. - F) * S));
                    
                    switch(Hi) {
                        case 0: RGB = <V, T, P>;
                        case 1: RGB = <Q, V, P>;
                        case 2: RGB = <P, V, T>;
                        case 3: RGB = <P, Q, V>;
                        case 4: RGB = <T, P, V>;
                        default: RGB = <V, P, Q>;
                    }
                    
                    return RGB;
                } 
               
                
                Void FlashButton(CMlQuad Button) {
                        declare Real amplitude = MathLib::Abs(MathLib::Sin(Now/1000.));
				        declare Vec3 color = <36., 1., amplitude>;			
				        Button.BgColor = HsvToRgb(color);	                
                }
                
                ***FML_OnInit***
                ***      
                    Exp_Window.ZIndex = 50.;
                    declare Text[][Text] Exp_Chat_UpdateText for This = Text[][Text];
                    declare Text[] Exp_Chat_UpdateConsole for This = Text[];
                    declare Text Exp_Chat_check for This = "";
                    declare Text Exp_Chat_oldcheck = "";
                    
                    declare Text[] ConsoleMessages = Text[];
                    declare Text[][Text] PrivateMessages = Text[][Text];
                    declare Boolean NewConsoleMessage = False;
                    declare Boolean NewPrivateMessage = False;
                    
                    ClientUI.OverlayHideChat = False;
                    ClientUI.OverlayChatOffset = <-0.04 , 0.>;
                    
                    declare CMlFrame PrivateMessagesFrame <=> (Page.GetFirstChild("PrivateMessages") as CMlFrame);
                    declare CMlFrame ServerMessagesFrame <=> (Page.GetFirstChild("ServerMessages") as CMlFrame); 
                    
                    declare CMlQuad ButtonServer <=> (Page.GetFirstChild("ButtonServer") as CMlQuad);
                    declare CMlQuad ButtonPrivate <=> (Page.GetFirstChild("ButtonPrivate") as CMlQuad);  
                    declare CMlQuad ButtonPublic <=> (Page.GetFirstChild("ButtonPublic") as CMlQuad);
                                                     
                ***
                
                ***Exp_ButtonPublic***
                ***
                     ClientUI.OverlayHideChat = False;
                     PrivateMessagesFrame.Hide();  
                     ServerMessagesFrame.Hide();
                     Toggle(ButtonPublic);                                      
                ***    
                
                 
                ***Exp_ButtonPrivate***
                ***
                     ClientUI.OverlayHideChat = True;
                     PrivateMessagesFrame.Show();
                     ServerMessagesFrame.Hide();
                     Toggle(ButtonPrivate);                
                ***
                
                
                ***Exp_ButtonServer***
                ***
                     ClientUI.OverlayHideChat = True;
                     PrivateMessagesFrame.Hide(); 
                     ServerMessagesFrame.Show(); 
                     NewConsoleMessage = False;  
                     Toggle(ButtonServer);       
                ***                                
                                 
                ***Exp_Chat_Update***
                ***
                                   
                    if (Exp_Chat_UpdateConsole.count > 0) {
                        NewConsoleMessage = True;                   
                        foreach (Message in Exp_Chat_UpdateConsole) {
                           ConsoleMessages.add(Message);                          
                        }                        
                            
                        if (ConsoleMessages.count >= 9) {                      
                            for (x, 0, ConsoleMessages.count - 9) {                                
                                ConsoleMessages.removekey(0);
                            }
                        }
                                                                                                                                                                                                   
                        for(x,0,7) {                        
                            if (ConsoleMessages.existskey(x)) {                          
                                (Page.GetFirstChild("serverline_"^x) as CMlLabel).Value = ConsoleMessages[x];
                            } else {
                                (Page.GetFirstChild("serverline_"^x) as CMlLabel).Value = "";
                            }                         	
                        }                        
                    } 
                     
                ***
                                                                               
                ***FML_Loop***
                *** 
                    if (Exp_Chat_check != Exp_Chat_oldcheck) {                      
                         Exp_Chat_oldcheck = Exp_Chat_check;                 
                        ---Exp_Chat_Update---                                                               
                    }
                    
                    if (NewConsoleMessage == True) {
                	   // FlashButton(ButtonServer);
                    }
                    
                    if (NewPrivateMessage == True) {
                	    FlashButton(ButtonPrivate);
                    }
                    
                    
                     
                ***

EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::MouseClick, <<<EOL
                if (Event.ControlId == "ButtonPublic") {                           
                ---Exp_ButtonPublic---
                }
 
                if (Event.ControlId == "ButtonPrivate") {
                ---Exp_ButtonPrivate---
                
                }
                if (Event.ControlId == "ButtonServer") {
                ---Exp_ButtonServer---
                }
                
EOL
        );


    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
    }


}
