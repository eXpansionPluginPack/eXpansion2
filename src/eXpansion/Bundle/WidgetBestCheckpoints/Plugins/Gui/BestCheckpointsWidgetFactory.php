<?php

namespace eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class BestCheckpointsWidgetFactory extends WidgetFactory
{
    const rowCount = 2;
    const columnCount = 10;

    /** @var UpdaterWidgetFactory */
    protected $updaterWidgetFactory;

    /***
     * MenuFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WidgetFactoryContext $context
     * @param UpdaterWidgetFactory $updaterWidgetFactory
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        UpdaterWidgetFactory $updaterWidgetFactory
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->updaterWidgetFactory = $updaterWidgetFactory;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $elementCount = 0;
        $rows = $this->uiFactory->createLayoutRow(0, 0, [], -0.5);
        $cpVariable = $this->updaterWidgetFactory->getVariable('LocalRecordCheckpoints')->getVariableName();

        for ($i = 0; $i < self::rowCount; $i++) {
            $elements = [];
            for ($c = 0; $c < self::columnCount; $c++) {
                if ($elementCount == 0) {
                    $dropdown = $this->uiFactory->createDropdown("select", [
                        "Live 1" => "1",
                        "Local 1" =>
                            "2",
                    ],
                        0,
                        false);
                    $dropdown->setWidth(18)->setId("Dropdown");
                    $elements[] = $dropdown;
                } else {
                    $elements[] = $this->createColumnBox($elementCount);
                }
                $elementCount++;
            }
            $line = $this->uiFactory->createLayoutLine(0, 0, $elements, 0.5);

            $rows->addChild($line);
        }
        $manialink->addChild($rows);

        $elementCount -= 1;
        /**
         * Functions
         */
        $manialink->getFmlManialink()->getScript()->addScriptFunction("",
            <<<EOL
            
            ***onSelectDropdown***
            ***
              declare Integer BestCp_Mode for LocalUser = 0; 
               BestCp_Mode = TextLib::ToInteger(uiDropdown.DataAttributeGet("selected"));
               Refresh();        
            ***
            
            
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
                    return color ^ sign ^ "00:" ^ FormatSec(sec, color, highlite);
                }
                                                            
                if (min > 10)  {
                   return highlite ^ sign ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);
                } 
                
                return color ^ sign ^ 0 ^ highlite ^ min ^ ":" ^ FormatSec(sec, highlite, highlite);                  
                                                                     
            }


            Void UpdateCp(Integer _Index, Integer _Score, Boolean _Animate) {
                 declare Integer ElementCount for Page = $elementCount;
                if (_Index > ElementCount) {
                    return;
                }
                {$this->updaterWidgetFactory->getScriptInitialization()}
                declare Integer[Integer] MapBestCheckpoints for Page = Integer[Integer];                                
                declare CMlLabel Label <=> (Page.GetFirstChild("Cp_"^ (_Index+1)) as CMlLabel);
                declare CMlQuad Bg <=> (Page.GetFirstChild("Bg_"^ (_Index+1)) as CMlQuad);          
                                                                              
                declare Integer BestCp_Mode for LocalUser = 0;                
                declare Text Color = "\$fff";
                Bg.BgColor = TextLib::ToColor("000");                           
                
                declare Integer Compare = 99999999;            
                
                switch (BestCp_Mode) {
                    case 0: {
                        if (MapBestCheckpoints.existskey(_Index)) {
                            Compare = MapBestCheckpoints[_Index];                            
                        } else {
                            Compare = 99999999;
                        }                 
                    }
                    case 1: {                      
                        if ($cpVariable.existskey(_Index)) {
                            Compare = {$cpVariable}[_Index];                            
                        } else {
                            Compare = 99999999;
                        }                       
                    }                                   
                }
                                 
                if (_Score == 99999999) {                                             
                    if ( Compare > 0 && Compare != 99999999  ) {
                        Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$bcc" ^ TimeToText(Compare);                                
                    } else {
                       Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$bcc --:--.---";
                    }                    
                } else {
                    if (_Score < Compare) {                    
                       Bg.BgColor = TextLib::ToColor("00f");
                       Color = "\$fff";
                    } else {
                       Bg.BgColor = TextLib::ToColor("f00");
                       Color = "\$fff";                                                                                                 
                    }
                    // Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$bcc" ^ TimeToText(Compare) ^ "\$fff" ^ " diff: " ^ Color ^ TimeToText(_Score - Compare); 
                    Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$bcc" ^ TimeToText(_Score - Compare); 
                }                                                
            }
            
            Void Refresh() {
                declare Integer ElementCount for Page = $elementCount;  
                declare Integer[Integer] MyCheckpoints for Page = Integer[Integer];       
                declare Integer[Integer] MapBestCheckpoints for Page = Integer[Integer];                                                
                declare Integer BestCp_Mode for LocalUser = 0; 
                                                                                      
                
                for (k, 0, (ElementCount-1)) {                                                       
                    if (MyCheckpoints.existskey(k)) {
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

        /**
         * ON INIT
         */
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit,
            <<<EOL
            declare Integer ElementCount for Page = $elementCount;
            declare Integer BestCp_Mode for LocalUser = 0; 
            declare CMlFrame Dropdown = (Page.GetFirstChild("Dropdown") as CMlFrame);    
            declare Integer[Integer] MyCheckpoints for Page = Integer[Integer];           
            declare Integer[Integer] MapBestCheckpoints for Page = Integer[Integer];              
            
            {$this->updaterWidgetFactory->getScriptInitialization()}
                     
            declare Integer[Integer] CompareCheckpoints for Page = Integer[Integer];            
            declare Integer MapBestTime = 99999999;
                                                        
            // clear
            for (i, 0, (ElementCount-1)) {
                MapBestCheckpoints[i] = 99999999;                                
            }
                                                             
            // hide checkpoints not needed            
            for (i, (MapCheckpointPos.count+2), (ElementCount)) {
                HideCp(i);
            }
            
            if (InputPlayer != Null) {
                foreach (k => i in InputPlayer.CurLap.Checkpoints) {
                            MyCheckpoints[k] = i;
                }
            }
            
            if (Scores.count > 0) {                                           
                if (Scores[0].BestLap.Time != -1) {
                    MapBestTime = Scores[0].BestLap.Time;             
                    foreach (k => i in Scores[0].BestLap.Checkpoints) {
                        MapBestCheckpoints[k] = i;
                    }                           
                }
            }
                 
            Refresh();
            
            Dropdown.DataAttributeSet("selected", ""^BestCp_Mode);
            uiRenderDropdown(Dropdown);
                                     
EOL
        );

        /**
         * Loop
         */
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
            
            // handle new record
            {$this->updaterWidgetFactory->getScriptOnChange('Refresh();')}
                                    
            foreach (RaceEvent in RaceEvents) {
                if (GUIPlayer == RaceEvent.Player && RaceEvent.Type == CTmRaceClientEvent::EType::Respawn) {
                     if (GUIPlayer.RaceState == CTmMlPlayer::ERaceState::BeforeStart) {                     
                        MyCheckpoints = Integer[Integer];               
                        Refresh();
                     }
                }             
                
                if (RaceEvent.Type == CTmRaceClientEvent::EType::WayPoint) {                               
                    
                    if (RaceEvent.IsEndRace || RaceEvent.IsEndLap) {
                    
                        if (RaceEvent.LapTime < MapBestTime) {
                            MapBestTime = RaceEvent.LapTime; 
                            if (GUIPlayer == RaceEvent.Player) {
                                MyCheckpoints[RaceEvent.CheckpointInLap] = RaceEvent.LapTime;
                                MapBestCheckpoints = MyCheckpoints;   
                                MyCheckpoints = Integer[Integer];
                                Refresh();                            
                            } else {                                                                                    
                                foreach (k => i in RaceEvent.Player.Score.BestLap.Checkpoints) {
                                    MapBestCheckpoints[k] = i;
                                }
                                Refresh();                                    
                            } 
                                                                                                                                                                                                                                                                                                                                                               
                                                    
                        } else {                          
                            if (GUIPlayer == RaceEvent.Player && $cpVariable.count > 0 && 
                            MapBestTime != 99999999) {                                                                                          
                                if (RaceEvent.IsEndLap && RaceEvent.IsEndRace == False) {                                        
                                    MyCheckpoints = Integer[Integer]; 
                                    Refresh();
                                } else {                                                                                                              
                                    UpdateCp(RaceEvent.CheckpointInLap, RaceEvent.LapTime, True);
                                }                                                                                                                                              
                            }
                        }                       
                    } else {                                            
                        if (GUIPlayer == RaceEvent.Player && RaceEvent.CheckpointInLap < ElementCount) {            
                            MyCheckpoints[RaceEvent.CheckpointInLap] = RaceEvent.LapTime;
                            
                            switch (BestCp_Mode) {
                                case 0:  {
                                    if (MapBestTime != 99999999) {                                         
                                        UpdateCp(RaceEvent.CheckpointInLap, RaceEvent.LapTime, True);
                                    }
                                }
                                case 1: {
                                   if ($cpVariable.count > 0) {
                                        UpdateCp(RaceEvent.CheckpointInLap, RaceEvent.LapTime, True);
                                   }                                
                                }                            
                            }                             
                        }
                    }
                }
            }
EOL
        );


    }

    /**
     * @param int $index
     * @return Frame
     */
    private function createColumnBox($index)
    {
        $width = 18;
        $height = 4;

        $frame = Frame::create();

        $label = $this->uiFactory->createLabel();
        $label->setAlign("left", "center2");
        $label->setTextSize(1)->setPosition(1, -($height / 2));
        $label->setSize($width, $height)
            ->setId("Cp_".$index);
        $frame->addChild($label);

        $background = Quad::create("Bg_".$index);
        $background->setSize($width, $height)
            ->setBackgroundColor("001")->setOpacity(0.3);
        $frame->addChild($background);

        $frame->setSize($width, $height);

        return $frame;
    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
    }


}
