<?php

namespace eXpansionExperimantal\Bundle\WidgetLiveRankings\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\Label;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class LiveRankingsWidgetFactory extends WidgetFactory
{

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $this->createScript($manialink);

        $row = $this->uiFactory->createLayoutRow(0, 0, [], 0.25);

        for ($x = 0; $x < 15; $x++) {
            $pos = $this->uiFactory->createLabel(($x + 1).".", Label::TYPE_TITLE);
            $pos->setSize(5, 3)->setHorizontalAlign("right")->setTextSize(1.75)->setOpacity(0.6);

            $nick = $this->uiFactory->createLabel("unknown", Label::TYPE_HEADER);
            $nick->setSize(25, 3)->setHorizontalAlign("left")->setTextSize(1.75)->setOpacity(0.6);

            $flag = Quad::create();
            $flag->setSize(3, 2.25)->setAlign("center", "center2")
                ->setY(-1.25)->setOpacity(0.6);

            $time = $this->uiFactory->createLabel("--:--.---", Label::TYPE_TITLE);
            $time->setSize(10, 3)->setHorizontalAlign("right")->setTextSize(1.75)->setOpacity(0.6);

            $line = $this->uiFactory->createLayoutLine(0, 0, [$pos, $flag, $time, $nick], 1);
            $line->addClass("PlayerItem");

            $row->addChild($line);
        }

        $manialink->addChild($row);

    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    private function createScript(ManialinkInterface $manialink)
    {
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
          declare Text oldTime = "";      
EOL
        );
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop, <<<EOL
          
              if (CurrentLocalDateText != oldTime) {
                 updateScores();
              }            
EOL
        );


        $manialink->getFmlManialink()->getScript()->addScriptFunction("", <<<EOL
          
            Text FormatSec(Real sec, Text color, Text highlite) {
                if (sec > 10.) {
                    return highlite ^ TextLib::FormatReal(sec,3,False,False);
                } 
                return highlite ^ TextLib::FormatReal(sec,3,False,False);                                
            }
            
            Text TimeToText(Integer intime, Text highlite) {                
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
                    return color ^ FormatSec(sec, color, highlite);
                }
                                                            
                if (min > 10)  {
                   return highlite ^ sign ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);
                } 
                
                return color ^ sign ^ highlite ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);                  
                                                                     
            }

            Void updateScores() {            
                Page.GetClassChildren ("PlayerItem", Page.MainFrame, True);
                declare CTmMlPlayer[Text] PlayersByLogin = CTmMlPlayer[Text];
                
                foreach (Player in Players) {
                    PlayersByLogin[Player.User.Login] <=> Player;
                }
                                                
                foreach (key => Item in Page.GetClassChildren_Result) {
                    declare Frame <=> (Item as CMlFrame);                    
                                        
                    if (Scores.existskey(key) && Scores[key].BestLap.Time > -1) {         
                        Frame.Show();                                                                            
                        declare Text Login = Scores[key].User.Login;                    
                                                                                                                                                                                            
                        (Frame.Controls[1] as CMlQuad).ImageUrl = Scores[key].User.CountryFlagUrl;
                        if (key == 0) {
                            (Frame.Controls[2] as  CMlLabel).Value =  TimeToText(Scores[key].BestLap.Time, "\$0f0");
                        } else {
                            (Frame.Controls[2] as  CMlLabel).Value = "+" ^ TimeToText(MathLib::Abs(Scores[0].BestLap.Time - Scores[key].BestLap.Time), "\$ff0");
                        }
                                                
                        (Frame.Controls[3] as CMlLabel).Value =  Scores[key].User.Name;
                        
                    } else {
                       Frame.Hide();
                    }
                }
            }

EOL
        );

    }


}
