<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class CustomCheckpointWidget extends WidgetFactory
{

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
     * @inheritdoc
     */
    protected function createManialink(Group $group, $hideable = true)
    {
        return parent::createManialink($group, false);
    }

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $frame = Frame::create("Frame_Main");
        $manialink->addChild($frame);

        $lbl = $this->uiFactory->createLabel("0  \$bcc-:--.---", uiLabel::TYPE_TITLE, "CurrentTime");
        $lbl->setPosition(0, -70)->setAlign("center", "center2")->setTextSize(3);
        $frame->addChild($lbl);

        $quad = Quad::create("quad_bg");
        $quad->setPosition(0, -70)->setSize(35, 6)->setBackgroundColor("000")
            ->setOpacity(0.5)->setAlign("center", "center");
        $frame->addChild($quad);

        $manialink->getFmlManialink()->getScript()->addScriptFunction("", <<<EOL
            Text FormatSec(Real sec, Text color, Text highlite) {
                if (sec > 10.) {
                    return highlite ^ TextLib::FormatReal(sec,3,False,False);
                } 
                return color ^ 0 ^ highlite ^ TextLib::FormatReal(sec,3,False,False);                                
            }
            
            Text TimeToText(Integer intime) {
                declare Text highlite = "\$eff";
                declare Text color = "\$bcc";
                declare time = MathLib::Abs(intime);                	
                declare Integer cent = time % 1000;	
                declare Integer sec2 = (time / 1000) % 60;
                declare Real sec = 1. * sec2 + cent * 0.001;
                declare Integer min = (time / 60000) % 60;                                                
                declare Integer hour = time / 3600000;
                declare Text sign = "";
                if (intime < 0)  {
                    sign = "-";
                }
                
                if (hour > 0) {
                    return highlite ^ sign ^ hour ^ "'" ^ TextLib::FormatInteger(min,2) ^ ":" ^ FormatSec(sec, highlite,highlite);
                }
                
                if (min == 0) {
                    return color ^ sign ^ "0:" ^ FormatSec(sec, color, highlite);
                }
                                                            
                if (min > 10)  {
                   return highlite ^ sign ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);
                } 
                
                return color ^ sign ^ 0 ^ highlite ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);                  
                                                                     
            }

EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
            declare CMlLabel CheckPointLabel = (Page.GetFirstChild("CurrentTime") as CMlLabel);        
            declare CMlQuad Bg = (Page.GetFirstChild("quad_bg") as CMlQuad);                                      
            declare CMlFrame Frame = (Page.GetFirstChild("Frame_Main") as CMlFrame);
            
            declare IsIntro = (
                UI.UISequence != CUIConfig::EUISequence::Playing                
            );              
            
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop, <<<EOL
            if (Frame.Visible && IsIntro) {
                Frame.Visible = False;
              
            } else if (!Frame.Visible && !IsIntro) {            
                Frame.Visible = True;                        
            }                      
               foreach (RaceEvent in RaceEvents) {
                if (GUIPlayer == RaceEvent.Player && RaceEvent.Type == CTmRaceClientEvent::EType::Respawn) {
                     if (InputPlayer.RaceState == CTmMlPlayer::ERaceState::BeforeStart) {                     
                        CheckPointLabel.Value = "0  \$bcc-:--.---";
                        Bg.BgColor = <0., 0., 0.>;                        
                     }
                }             
                
                if (GUIPlayer == RaceEvent.Player && RaceEvent.Type == CTmRaceClientEvent::EType::WayPoint) {
                  
                    declare CTmResult Score <=> RaceEvent.Player.Score.BestLap;
                   // TopBg.Show();                    
                    if (Score.Checkpoints.existskey(RaceEvent.CheckpointInLap) ) {
                        CheckPointLabel.Value = (RaceEvent.CheckpointInLap+1) ^ "  " ^ TimeToText(RaceEvent
                        .LapTime - Score.Checkpoints[RaceEvent.CheckpointInLap]);
                        if (RaceEvent.LapTime < Score.Checkpoints[RaceEvent.CheckpointInLap]) {
                            Bg.BgColor = <0., 0., 1.>;                            
                        } else {
                            Bg.BgColor = <1., 0., 0.>;                            
                        }
                    } else {
                       CheckPointLabel.Value = (RaceEvent.CheckpointInLap+1) ^ "  " ^ TimeToText(RaceEvent
                        .LapTime);                     
                    }
                                                                                               
                }
          }
EOL
        );
    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
    }


}
