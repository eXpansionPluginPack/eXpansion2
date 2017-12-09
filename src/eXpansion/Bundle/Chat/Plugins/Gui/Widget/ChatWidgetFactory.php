<?php

namespace eXpansion\Bundle\Chat\Plugins\Gui\Widget;

use eXpansion\Bundle\Chat\Plugins\Gui\Elements\Tab;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Controls\Entry;
use FML\Controls\Frame;
use FML\Controls\Label;

class ChatWidgetFactory extends WidgetFactory
{
    /**
     * @var ManiaScriptFactory
     */
    private $maniaScriptFactory;

    /***
     * MenuFactory constructor.
     *
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param WidgetFactoryContext $context
     * @param ManiaScriptFactory $maniaScriptFactory
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        ManiaScriptFactory $maniaScriptFactory

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->maniaScriptFactory = $maniaScriptFactory;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);


        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);


        $tabline = $this->uiFactory->createLayoutLine(0, 0, [], 1);
        $tabline->setId("Tabs");

        $tabPrototype = new Tab();
        $tabPrototype->setSize(20, 5)
            ->setBackgroundColor("000")
            ->setFocusBackgroundColor("f90");


        $tab = clone $tabPrototype;
        $tab->setText("Public chat")
            ->setId("ButtonPublic");
        $tabline->addChild($tab);

        $tab = clone $tabPrototype;
        $tab->setText("Console")
            ->setId("ButtonServer");
        $tabline->addChild($tab);

        for ($x = 0; $x < 5; $x++) {
            $tab = clone $tabPrototype;
            $tab->setText("Nickname $x")
                ->setId("tab_$x");
            $tabline->addChild($tab);
        }

        $tab = new Tab();
        $tab->setSize(5, 5)
            ->setText("+")
            ->setId("ButtonAdd")
            ->setBackgroundColor("0d0")
            ->setFocusBackgroundColor("f90");
        $tabline->addChild($tab);

        $manialink->addChild($tabline);

        $messageFrame = Frame::create("MessageFrame");
        $messageFrame->setVisible(false);
        $messageFrame->setPosition(0, -10);

        $linerow = $this->uiFactory->createLayoutRow(0, 0, [], 1);

        for ($x = 0; $x < 8; $x++) {
            $line = new Label();
            $line->setText("$x");
            $line->setStyle("TextRaceMessage")
                ->setId("line_$x")
                ->setSize(110, 4)
                ->setTextSize(1);
            $linerow->addChild($line);
        }

        $messageFrame->addChild($linerow);

        $manialink->addChild($messageFrame);

        $entry = Entry::create("TextEntry");
        $entry->setSize(2200, 4);

        $entry->setPosition(18, -4)
            ->setVisible(false)
            ->setAreaColor("0006")
            ->setAreaFocusColor("000a")
            ->setTextSize(2)
            ->setScriptEvents(true);

        $manialink->addChild($entry);


        $manialink->getFmlManialink()->addChild($this->maniaScriptFactory->createScript(['']));
        $this->createManiascript($manialink);


    }

    private function createManiaScript(Widget $manialink)
    {

        $manialink->getFmlManialink()->getScript()->addScriptFunction("", <<<EOL
              
                Void Toggle(CMlQuad Button) {
                      declare CMlQuad ButtonServer <=> (Page.GetFirstChild("ButtonServer") as CMlQuad);
                      declare CMlQuad ButtonPublic <=> (Page.GetFirstChild("ButtonPublic") as CMlQuad);
                                          
                      ButtonServer.BgColor = TextLib::ToColor("000");               
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
                    ClientUI.StatusMessage = "";    
                    Exp_Window.ZIndex = 50.;
                    declare Text[][Text] Exp_Chat_UpdateText for This = Text[][Text];
                    declare Text[] Exp_Chat_UpdateConsole for This = Text[];
                    declare Text Exp_Chat_check for This = "";
                    declare Text Exp_Chat_oldcheck = "";
                    
                    declare Text[] ConsoleMessages = Text[];
                    declare Boolean NewConsoleMessage = False;
                    declare Boolean NewPrivateMessage = False;                                     
                    
                    ClientUI.OverlayHideChat = False;
                    ClientUI.OverlayChatOffset = <-0.04 , 0.>;
                    
                    declare CMlFrame MessageFrame <=> (Page.GetFirstChild("MessageFrame") as CMlFrame);
                    declare CMlEntry TextEntry <=> (Page.GetFirstChild("TextEntry") as CMlEntry); 
                    
                    declare CMlQuad ButtonServer <=> (Page.GetFirstChild("ButtonServer") as CMlQuad);
                    declare CMlQuad ButtonPublic <=> (Page.GetFirstChild("ButtonPublic") as CMlQuad);
                                                     
                ***
                
                ***Exp_ButtonPublic***
                ***
                     ClientUI.OverlayHideChat = False;

                     MessageFrame.Hide();
                     Toggle(ButtonPublic);                                      
                ***    

                ***Exp_ButtonServer***
                ***         
                     ClientUI.OverlayHideChat = True;
                     MessageFrame.Show();                  
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
                                (Page.GetFirstChild("line_"^x) as CMlLabel).Value = ConsoleMessages[x];
                            } else {
                                (Page.GetFirstChild("line_"^x) as CMlLabel).Value = "";
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
                              
                     
                ***
                
                ***FML_KeyPress***
                ***
                if (ClientUI.OverlayHideChat == True && Event.KeyName == "Y" ) {
                       if (TextEntry.Visible) {
                            TextEntry.Hide();       
                       } else {
                            TextEntry.Show();
                            TextEntry.StartEdition();                            
                       }         
                }          
                ***
                
                ***FML_EntrySubmit***
                ***     
                        if (Event.ControlId == "TextEntry") {             
                            log((Event.Control as CMlEntry).Value);   
                        }
                ***
                
                ***FML_MouseClick***
                ***
                if (Event.ControlId == "ButtonPublic") {                           
                    ---Exp_ButtonPublic---
                }

                if (Event.ControlId == "ButtonServer") {
                    ---Exp_ButtonServer---
                }                
                ***
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
