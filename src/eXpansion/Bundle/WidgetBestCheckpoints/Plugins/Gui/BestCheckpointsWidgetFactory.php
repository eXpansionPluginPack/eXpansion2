<?php

namespace eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetBackground;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;
use FML\Script\ScriptInclude;
use FML\Script\ScriptLabel;

class BestCheckpointsWidgetFactory extends WidgetFactory
{
    const rowCount = 3;
    const columnCount = 6;

    /***
     * MenuFactory constructor.
     *
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param WidgetFactoryContext $context
     * @param Factory $uiFactory
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        Factory $uiFactory
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->uiFactory = $uiFactory;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $elementCount = 0;
        $rows = $this->uiFactory->createLayoutRow(0, 0, [], -1);

        for ($i = 0; $i < self::rowCount; $i++) {
            $elements = [];
            for ($c = 0; $c < self::columnCount; $c++) {
                $elements[] = $this->createColumnBox($elementCount);
                $elementCount++;
            }
            $line = $this->uiFactory->createLayoutLine(0, 0, $elements, 1);

            $rows->addChild($line);
        }

        $manialink->getFmlManialink()->getScript()->setScriptInclude(ScriptInclude::TextLib, "TextLib");
        $manialink->getFmlManialink()->getScript()->setScriptInclude(ScriptInclude::MathLib, "MathLib");


        $manialink->getFmlManialink()->getScript()->addScriptFunction("",
            <<<EOL
            
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


            Void UpdateCp(Integer _Index, Integer _Score, Boolean _Animate) {
                declare Integer[Integer] BestCheckpoints for Page = Integer[Integer];                
                declare CMlLabel Label <=> (Page.GetFirstChild("Cp_"^ (_Index+1)) as CMlLabel);
                declare CMlQuad Bg <=> (Page.GetFirstChild("Bg_"^ (_Index+1)) as CMlQuad);
                
                declare Text Color = "\$fff";
                Bg.BgColor = TextLib::ToColor("000");
                                              
                if (_Score == 99999999) {                                             
                    if (BestCheckpoints.existskey(_Index) && BestCheckpoints[_Index] != 99999999 ) {
                        Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$ff3" ^ TimeToText(BestCheckpoints[_Index]);                        
                    } else {
                       Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$ff3 --:--.---";
                    }                    
                } else {
                    if (_Score < BestCheckpoints[_Index]) {                    
                       Bg.BgColor = TextLib::ToColor("00f");
                       Color = "\$fff";
                    } else {
                       Bg.BgColor = TextLib::ToColor("f00");
                        Color = "\$fff";                                                                                                 
                    }
                    Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$ff3" ^ TimeToText(BestCheckpoints[_Index]) ^ "\$fff diff: " ^ Color ^ TimeToText(_Score - BestCheckpoints[_Index]); 
                }
                
                
                if (_Animate) {                                
                  //  AnimMgr.Add (Label, "<elem scale=\"1.5\" />", 350, CAnimManager::EAnimManagerEasing::ElasticOut);
                  //  AnimMgr.AddChain (Label, "<elem scale=\"1.0\" />", 350, CAnimManager::EAnimManagerEasing::ElasticIn);
                }          
            }
            
            Void Refresh() {
                declare Integer ElementCount for Page = $elementCount;  
                declare Integer[Integer] MyCheckpoints for Page = Integer[Integer];       
                declare Integer[Integer] BestCheckpoints for Page = Integer[Integer];
                            
                foreach (k => i in BestCheckpoints) {
                    if (MyCheckpoints.existskey(k) && i != 99999999) {
                        UpdateCp(k, MyCheckpoints[k], False);
                    } else {
                        UpdateCp(k, 99999999, False);          
                    }                 
                }                     
            }
                        
             Void HideCp(Integer _Index) {
                (Page.GetFirstChild("Cp_"^ _Index) as CMlLabel).Hide();
                (Page.GetFirstChild("Bg_"^ _Index) as CMlFrame).Hide();               
             }   
EOL

        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit,
            <<<EOL
            declare Integer ElementCount for Page = $elementCount;            
            declare Integer[Integer] MyCheckpoints for Page = Integer[Integer];           
            declare Integer[Integer] BestCheckpoints for Page = Integer[Integer];                        
            declare Integer BestTime = 99999999;
                                                        
            // clear
            for (i, 0, (ElementCount-2)) {
                BestCheckpoints[i] = 99999999;                                
            }
                                                             
            // hide checkpoints not needed            
            for (i, (MapCheckpointPos.count+2), (ElementCount-1)) {
                HideCp(i);
            }  
            
            foreach (k => i in InputPlayer.CurLap.Checkpoints) {
                    MyCheckpoints[k] = i;
            }
                                    
            if (Scores[0].BestLap.Time != -1) {
                BestTime = Scores[0].BestLap.Time;
                declare CMlLabel Label <=> (Page.GetFirstChild("Cp_0") as CMlLabel);
                Label.Value = Scores[0].User.Name;
                foreach (k => i in Scores[0].BestLap.Checkpoints) {
                    BestCheckpoints[k] = i;
                }
                           
            }
                       
            Refresh();
                                                                                      
EOL
        );


        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
            foreach (RaceEvent in RaceEvents) {
                if (InputPlayer == RaceEvent.Player && RaceEvent.Type == CTmRaceClientEvent::EType::Respawn) {
                     if (InputPlayer.RaceState == CTmMlPlayer::ERaceState::BeforeStart) {                     
                        MyCheckpoints = Integer[Integer];               
                        Refresh();
                     }
                }             
                
                if (RaceEvent.Type == CTmRaceClientEvent::EType::WayPoint) {                               
                    
                    if (RaceEvent.IsEndRace || RaceEvent.IsEndLap) {
                        if (RaceEvent.LapTime < BestTime) {
                            BestTime = RaceEvent.LapTime; 
                            if (InputPlayer == RaceEvent.Player) {
                                MyCheckpoints[RaceEvent.CheckpointInLap] = RaceEvent.LapTime;
                                BestCheckpoints = MyCheckpoints;   
                                MyCheckpoints = Integer[Integer];
                                Refresh();                            
                            } else {                                                                                    
                                foreach (k => i in RaceEvent.Player.Score.BestLap.Checkpoints) {
                                    BestCheckpoints[k] = i;
                                }       
                            }                                                                                                                                                                                                                                                                                                           
                            declare CMlLabel Label <=> (Page.GetFirstChild("Cp_0") as CMlLabel);
                            Label.Value = RaceEvent.Player.User.Name;
                            Refresh();                            
                        } else {                          
                            if (InputPlayer == RaceEvent.Player && BestTime != 99999999) {                                  
                                if (RaceEvent.IsEndLap && RaceEvent.IsEndRace == False ) {                                        
                                    MyCheckpoints = Integer[Integer]; 
                                    Refresh();
                                } else {                                                                                                              
                                    UpdateCp(RaceEvent.CheckpointInLap, RaceEvent.LapTime, True);
                                }                                                                                                                                              
                            }
                        }                       
                    } else {                    
                        if (InputPlayer == RaceEvent.Player && RaceEvent.CheckpointInLap < ElementCount) {            
                            MyCheckpoints[RaceEvent.CheckpointInLap] = RaceEvent.LapTime;
                            if (BestTime != 99999999) {                                                        
                                UpdateCp(RaceEvent.CheckpointInLap, RaceEvent.LapTime, True);
                            }
                        }                        
                    }
                }
            }
EOL
        );
        $manialink->addChild($rows);


    }

    /**
     * @param int $index
     * @return Frame
     */
    private function createColumnBox($index)
    {
        $width = 35;
        $height = 4;

        $frame = Frame::create();

        $label = $this->uiFactory->createLabel();
        $label->setAlign("left", "center");
        $label->setTextSize(1)->setPosition(1, -($height / 2));
        $label->setSize($width, $height)
            ->setId("Cp_".$index);
        $frame->addChild($label);

        $background = new WidgetBackground($width, $height);
        $background->setId("Bg_".$index);
        $frame->addChild($background);

        $frame->setSize($width, $height);

        return $frame;
    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink); // TODO: Change the autogenerated stub
    }


}
