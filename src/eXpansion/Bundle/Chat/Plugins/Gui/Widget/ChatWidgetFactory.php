<?php

namespace eXpansion\Bundle\Chat\Plugins\Gui\Widget;

use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetBackground;
use FML\Controls\Frame;
use FML\Controls\Label;
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

        $row = $this->uiFactory->createLayoutRow(0, 0, [], -1);
        $manialink->addChild($row);

        $btn = $this->uiFactory->createLabel();
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setScriptEvents(true)
            ->setText("")
            ->setAreaColor("000a")
            ->setAreaFocusColor("f90a")
            ->setId("ButtonPublic");

        $tooltip->addTooltip($btn, "Public Chat");
        $row->addChild($btn);


        $btn = $this->uiFactory->createLabel();
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setScriptEvents(true)
            ->setTextPrefix("")
            ->setAreaColor("000a")
            ->setAreaFocusColor("f90a")
            ->setId("ButtonPrivate");

        $tooltip->addTooltip($btn, "Private Messages");
        $row->addChild($btn);

        $btn = $this->uiFactory->createLabel();
        $btn->setSize(6, 6)
            ->setAlign("center", "center2")
            ->setTextSize(3)
            ->setScriptEvents(true)
            ->setTextPrefix("")
            ->setAreaColor("000a")
            ->setAreaFocusColor("f90a")
            ->setId("ButtonServer");

        $tooltip->addTooltip($btn, "Server Console");
        $row->addChild($btn);
        $manialink->addChild($btn);

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
                ->setAreaColor("000a")
                ->setAreaFocusColor("f90a")
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
                ->setSize(90, 5)
                ->setTextSize(2);
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

        for ($x = 0; $x < 7; $x++) {
            $line = new Label();
            $line->setStyle("TextRaceMessage")
                ->setId("serverline_$x")
                ->setSize(90, 5)
                ->setTextSize(2);
            $linerow->addChild($line);
        }

        $serverFrame->addChild($linerow);

        $manialink->addChild($serverFrame);



        $serverFrame = Frame::create("ServerMessages");
        $serverFrame->setVisible(false);
        $serverFrame->setPosition(6, -12);


        $manialink->addChild($serverFrame);


        $bg = new WidgetBackground(110, 50);
        $bg->setPosition(6, -16);
        $manialink->addChild($bg);

        $this->createManiascript($manialink);


    }

    private function createManiaScript(Widget $manialink)
    {

        $manialink->getFmlManialink()->getScript()->addScriptFunction("", <<<EOL
               
                ***FML_OnInit***
                ***
                    declare Text Exp_Chat_UpdateLogin for This = "";         
                    declare Text Exp_Chat_UpdateText for This = "";
                    declare Text Exp_Chat_UpdateConsole for This = "";
                    declare Text Exp_Chat_check for This = "";
                    declare Text Exp_Chat_oldcheck = "";
                    declare Text[] ConsoleMessages = Text[];
                    declare Text[][Text] PrivateMessages = Text[][Text];
                
                
                  ClientUI.OverlayHideChat = False;
                  ClientUI.OverlayChatOffset = <-0.04 , 0.>;
                  declare CMlFrame PrivateMessagesFrame <=> (Page.GetFirstChild("PrivateMessages") as CMlFrame);
                  declare CMlFrame ServerMessagesFrame <=> (Page.GetFirstChild("ServerMessages") as CMlFrame);                
                ***
                 
                ***Exp_ButtonPublic***
                ***
                     ClientUI.OverlayHideChat = False;
                     PrivateMessagesFrame.Hide();  
                     ServerMessagesFrame.Hide(); 
                                       
                ***    
                
                 
                ***Exp_ButtonPrivate***
                ***
                     ClientUI.OverlayHideChat = True;
                     PrivateMessagesFrame.Show();
                     ServerMessagesFrame.Hide();                
                ***
                
                
                ***Exp_ButtonServer***
                ***
                     ClientUI.OverlayHideChat = True;
                     PrivateMessagesFrame.Hide(); 
                     ServerMessagesFrame.Show();                       
                ***                                
                                 
                ***Exp_Chat_Update***
                ***
                     if (Exp_Chat_UpdateLogin != "") {
                        log(Exp_Chat_UpdateLogin);                     
                     }
                     
                     if (Exp_Chat_UpdateConsole != "") {
                       
                        if (ConsoleMessages.count >= 7) {
                            ConsoleMessages.removekey(0);
                        }
                        
                        ConsoleMessages.add(Exp_Chat_UpdateConsole);                                                                                                                                                                                
                        for(x,0,6) {                        
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
