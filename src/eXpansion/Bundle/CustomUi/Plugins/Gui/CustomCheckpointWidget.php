<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
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
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $frame = Frame::create("Frame_Main");
        $manialink->addChild($frame);

        $lbl = $this->uiFactory->createLabel("Cp: 0  00:00.000", uiLabel::TYPE_TITLE, "CurrentTime");
        $lbl->setPosition(0, -70)->setAlign("center", "center2")->setTextSize(4);

        $frame->addChild($lbl);

        $quad = Quad::create("quad_bg");
        $quad->setPosition(0, -70)->setSize(44, 8)->setBackgroundColor("000")
            ->setOpacity(0.5)->setAlign("center", "center2");
        $frame->addChild($quad);

        $quad = Quad::create("quad_top");
        $quad->setPosition(0, -80)->setSize(400, 33)
            ->setStyles("BgsPlayerCard", "BgRacePlayerLine")
            ->setColorize("777")->setAlign("center", "center")->setRotation(180);
        $frame->addChild($quad);


        $manialink->getFmlManialink()->getScript()->addScriptFunction("", <<<EOL
        Text TimeToText(Integer intime) {
                declare time = MathLib::Abs(intime);
                declare Integer cent = time % 1000;	
                declare Integer sec = (time / 1000) % 60;
                declare Integer min = time / 60000;
                declare Integer hour = time / 3600000;
                declare Text sign = "";
                if (intime < 0)  {
                    sign = "-";
                }
                
                if (hour > 0) {
                    return sign ^ hour ^ ":" ^ TextLib::FormatInteger(min,2) ^ ":" ^ TextLib::FormatInteger(sec,2) ^ "." ^ TextLib::FormatInteger(cent,3);
                } 
                return sign ^ TextLib::FormatInteger(min,2) ^ ":" ^ TextLib::FormatInteger(sec,2) ^ "." ^ TextLib::FormatInteger(cent,3);                                
            }
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
            declare CMlLabel CheckPointLabel = (Page.GetFirstChild("CurrentTime") as CMlLabel);
            declare CMlQuad TopBg = (Page.GetFirstChild("quad_top") as CMlQuad);
            declare CMlQuad Bg = (Page.GetFirstChild("quad_bg") as CMlQuad);                                      
            declare CMlFrame Frame = (Page.GetFirstChild("Frame_Main") as CMlFrame);
            
            declare IsIntro = (
                UI.UISequence == CUIConfig::EUISequence::Intro ||
                UI.UISequence == CUIConfig::EUISequence::RollingBackgroundIntro ||
                UI.UISequence == CUIConfig::EUISequence::Outro
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
                        CheckPointLabel.Value = "Cp: 0  00:00.000";
                        Bg.BgColor = <0., 0., 0.>;
                        TopBg.Hide();
                     }
                }             
                
                if (GUIPlayer == RaceEvent.Player && RaceEvent.Type == CTmRaceClientEvent::EType::WayPoint) {
                  
                    declare CTmResult Score <=> RaceEvent.Player.Score.BestLap;
                   // TopBg.Show();                    
                    if (Score.Checkpoints.existskey(RaceEvent.CheckpointInLap) ) {
                        CheckPointLabel.Value = "Cp: " ^(RaceEvent.CheckpointInLap+1) ^ "  " ^ TimeToText(RaceEvent
                        .LapTime - Score.Checkpoints[RaceEvent.CheckpointInLap]);
                        if (RaceEvent.LapTime < Score.Checkpoints[RaceEvent.CheckpointInLap]) {
                            Bg.BgColor = <0., 0., 1.>;
                            TopBg.Colorize = <0., 0., 1.>;
                        } else {
                            Bg.BgColor = <1., 0., 0.>;
                            TopBg.Colorize = <1., 0., 0.>;
                        }
                    } else {
                       CheckPointLabel.Value = "Cp: " ^(RaceEvent.CheckpointInLap+1) ^ "  " ^ TimeToText(RaceEvent
                        .LapTime);
                       TopBg.Colorize = <0.7, 0.7, 0.7>;
                    }
                                                                                               
                }
          }
EOL
        );
    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink); // TODO: Change the autogenerated stub
    }


}
