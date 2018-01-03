<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Script\ScriptLabel;

class MarkersWidget extends WidgetFactory
{

    /**
     * ChatHelperWidget constructor.
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param                      $posX
     * @param                      $posY
     * @param WidgetFactoryContext $context
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
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $manialink->getFmlManialink()->getScript()->addScriptFunction("",
            /** @lang text */
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
            
            Void UpdateData() {                    
                declare Integer[Vec3] MapBestCheckpoints for Page = Integer[Vec3];                                    
                declare Text[Vec3] MapBestNicknames for Page = Text[Vec3];
                declare Vec3[] Positions for Page = Vec3[];          
                   
                declare xml = "";
              
                foreach(pos in Positions) {          
                if (MapBestCheckpoints[pos] != 99999999) {                                                                                                           
                    xml ^= "<marker label='"^MapBestNicknames[pos] ^ " " ^ TimeToText(MapBestCheckpoints[pos]) ^ 
                    "' pos='"^pos.X^" "^(pos.Y + 3.)^" "^pos.Z^"' distmax='300' />";
                    }                                                                               
                }
                ClientUI.MarkersXML = xml;                    
            }
                                
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit,
            /** @lang ManiaScript */
            <<<EOL
            declare Integer[Vec3] MapBestCheckpoints for Page = Integer[Vec3];                                    
            declare Text[Vec3] MapBestNicknames for Page = Text[Vec3];
            declare Vec3[] Positions for Page = Vec3[];                                                                 
                
                    
                                                                       
            // clear
            foreach (position in MapCheckpointPos) {
                MapBestCheckpoints[position] = 99999999;
                MapBestNicknames[position] = "";       
                Positions.add(position);
            }            
                        
            UpdateData();                                                                            
EOL
        );

        /**
         * Loop
         */
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,

            /** @lang text */
            <<<EOL
                                
            foreach (RaceEvent in RaceEvents) {                                
                if (RaceEvent.Type == CTmRaceClientEvent::EType::WayPoint) {                                                                                              
                    declare Vec3 pos = RaceEvent.Player.Position;
                    declare Vec3 key = Vec3;
                    declare Boolean found =  False;
                    foreach(cpPos in Positions) {                                              
                        if (MathLib::Distance (pos, cpPos) < 12.5) {                        
                            key = cpPos;      
                            found = True;                             
                            break;                     
                        }
                    }
                    
                    if (found) {                        
                        if (RaceEvent.LapTime < MapBestCheckpoints[key]) {
                            MapBestCheckpoints[key] = RaceEvent.LapTime;
                            MapBestNicknames[key] = RaceEvent.Player.User.Name;
                            UpdateData();
                        }
                    }
                }        
            }
EOL
        );

    }


}
