<?php

namespace eXpansion\Bundle\WidgetCurrentMap\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Script\ScriptLabel;

class CurrentMapWidgetFactory extends WidgetFactory
{

    /***
     * MenuFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WidgetFactoryContext $context
     * @param Factory              $uiFactory
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
        parent::createContent($manialink);


        $rows = $this->uiFactory->createLayoutRow(0, 0, [], -1);
        $manialink->addChild($rows);


        $lbl = $this->uiFactory->createLabel("empty value", uiLabel::TYPE_TITLE, "MapName");
        $lbl->setAlign("right", "center");
        $lbl->setTextSize(3)->setSize(40, 4);
        $rows->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("empty value", uiLabel::TYPE_NORMAL, "AuthorName");
        $lbl->setAlign("right", "center");
        $lbl->setTextSize(2)->setSize(40, 4);
        $rows->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("empty value", uiLabel::TYPE_TITLE, "AuthorTime");
        $lbl->setAlign("right", "center");
        $lbl->setTextSize(1)->setSize(20, 4);
        $rows->addChild($lbl);


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
            
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit,
            <<<EOL
            
            (Page.GetFirstChild("MapName") as CMlLabel).Value = Map.MapName;            
            (Page.GetFirstChild("AuthorName") as CMlLabel).Value = Map.AuthorNickName;
            if (Map.TMObjective_AuthorTime > -1) {
                (Page.GetFirstChild("AuthorTime") as CMlLabel).Value = TimeToText(Map.TMObjective_AuthorTime);
            } else {
            (Page.GetFirstChild("AuthorTime") as CMlLabel).Value = "";
            }                                        
EOL
        );

        $manialink->addChild($rows);


    }


    protected function updateContent(ManialinkInterface $manialink)
    {
        echo "updated!";
        parent::updateContent($manialink);
    }


}
