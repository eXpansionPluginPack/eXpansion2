<?php

namespace eXpansion\Bundle\WidgetCurrentMap\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\Gui\Components\uiLabel;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Script\ScriptLabel;

class CurrentMapWidgetFactory extends WidgetFactory
{
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;

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
     * @param GameDataStorage      $gameDataStorage
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        Factory $uiFactory,
        GameDataStorage $gameDataStorage

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->uiFactory = $uiFactory;
        $this->gameDataStorage = $gameDataStorage;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);



        $lbl = $this->uiFactory->createLabel("unknown map", uiLabel::TYPE_NORMAL, "MapName");
        $lbl->setAlign("center", "center");
        $lbl->setTextSize(1)->setSize(60, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0013")->setScriptEvents(true);

        $manialink->addChild($lbl);

        $line = $this->uiFactory->createLayoutLine(-(19/2)-1.5, -5, [], 1.5);
        $manialink->addChild($line);

        $lbl = $this->uiFactory->createLabel("0 / 0", uiLabel::TYPE_NORMAL, "Players");
        $lbl->setTextPrefix("👥  ");
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize(19, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0017")->setScriptEvents(true);
        $line->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("0 / 0", uiLabel::TYPE_NORMAL, "Spectators");
        $lbl->setTextPrefix("🎥  ");
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize(19, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0017")->setScriptEvents(true);
        $line->addChild($lbl);

        $ladderMin = $this->gameDataStorage->getServerOptions()->ladderServerLimitMin / 1000;
        $ladderMax = $this->gameDataStorage->getServerOptions()->ladderServerLimitMax / 1000;

        $lbl = $this->uiFactory->createLabel($ladderMin." - ".$ladderMax."k", uiLabel::TYPE_NORMAL);
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize(19, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0017")->setScriptEvents(true);
        $line->addChild($lbl);


        $playersMax = $ladderMax = $this->gameDataStorage->getServerOptions()->currentMaxPlayers;
        $spectatorMax = $ladderMax = $this->gameDataStorage->getServerOptions()->currentMaxSpectators;

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
    
           if (AllPlayerCount != Players.count) {
               AllPlayerCount = Players.count;
               declare Integer serverPlayers = 0;
               declare Integer serverSpectators = 0;
               
               foreach (Player in Players) {               
                   if (Player.RequestsSpectate) {
                        serverSpectators += 1;
                   } else {
                        serverPlayers += 1;
                   }               
               }                     
               
               (Page.GetFirstChild("Players") as CMlLabel).Value = serverPlayers ^ " / {$playersMax}";
               (Page.GetFirstChild("Spectators") as CMlLabel).Value = serverSpectators ^ " / {$spectatorMax}";
                              
           }   
    
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit,
            <<<EOL
            declare Integer AllPlayerCount = -1;                        
            (Page.GetFirstChild("MapName") as CMlLabel).Value = Map.AuthorNickName ^ "\$z\$s - " ^ Map.MapName;                                         
                                                            
EOL
        );

        $manialink->addChild($line);
    }


    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
    }

}
