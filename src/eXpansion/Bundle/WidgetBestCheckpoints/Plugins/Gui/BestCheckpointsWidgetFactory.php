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

        $manialink->getFmlManialink()->getScript()->setScriptInclude(ScriptInclude::TextLib);
        $manialink->getFmlManialink()->getScript()->addScriptFunction("",
            <<<EOL
            
            Void UpdateCp(Integer _Index, Integer _Score, Text _Nickname, Boolean _Animate) {
                declare CMlLabel Label <=> (Page.GetFirstChild("Cp_"^ _Index) as CMlLabel);
                                
                if (_Score == -1) {
                    Label.Value = "\$fff\$o" ^ (_Index+1);
                } else {
                    Label.Value = "\$fff\$o" ^ (_Index+1) ^ " \$o\$ff3" ^ TextLib::TimeToText(_Score, True) ^" \$fff "^ TextLib::StripFormatting(_Nickname); 
                }
                if (_Animate) {                                
                    AnimMgr.Add (Label, "<elem scale=\"1.5\" />", 350, CAnimManager::EAnimManagerEasing::ElasticOut);
                    AnimMgr.AddChain (Label, "<elem scale=\"1.0\" />", 350, CAnimManager::EAnimManagerEasing::ElasticIn);
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
            declare Integer[Integer] BestCheckpoints for Page = Integer[Integer];                          
            
            // clear
            for (i,0, (ElementCount-1)) {
                BestCheckpoints[i] = 99999999;           
                UpdateCp(i, -1, "", False);                
            }                             
                           
            // hide checkpoints not needed
            for (i, (MapCheckpointPos.count+1), (ElementCount-1)) {
                HideCp(i);
            }    
EOL
        );


        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
            foreach (RaceEvent in RaceEvents) {
                if (RaceEvent.Type == CTmRaceClientEvent::EType::WayPoint) {
                    if (RaceEvent.CheckpointInLap < ElementCount) {                    
                        if (RaceEvent.LapTime < BestCheckpoints[RaceEvent.CheckpointInLap]) {
                            BestCheckpoints[RaceEvent.CheckpointInLap] = RaceEvent.LapTime;
                            UpdateCp(RaceEvent.CheckpointInLap, RaceEvent.LapTime, RaceEvent.Player.User.Name, True);
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
        $width = 40;
        $height = 5;

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
