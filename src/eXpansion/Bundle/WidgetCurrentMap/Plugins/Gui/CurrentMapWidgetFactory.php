<?php

namespace eXpansion\Bundle\WidgetCurrentMap\Plugins\Gui;

use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalMapRatings\Services\MapRatingsService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Gui\Components\Label;
use FML\Script\ScriptLabel;

class CurrentMapWidgetFactory extends WidgetFactory
{

    /** @var Label */
    public $lblNo;

    /** @var Label */
    public $lblYes;
    /**
     * @var GameDataStorage
     */
    private $gameDataStorage;
    /**
     * @var MapRatingsService
     */
    private $mapRatingsService;
    /**
     * @var ChatCommandDataProvider
     */
    private $chatCommandDataProvider;

    /***
     * MenuFactory constructor.
     *
     * @param                         $name
     * @param                         $sizeX
     * @param                         $sizeY
     * @param null                    $posX
     * @param null                    $posY
     * @param WidgetFactoryContext    $context
     * @param GameDataStorage         $gameDataStorage
     * @param MapRatingsService       $mapRatingsService
     * @param ChatCommandDataProvider $chatCommandDataProvider
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        GameDataStorage $gameDataStorage,
        MapRatingsService $mapRatingsService,
        ChatCommandDataProvider $chatCommandDataProvider

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gameDataStorage = $gameDataStorage;
        $this->mapRatingsService = $mapRatingsService;
        $this->chatCommandDataProvider = $chatCommandDataProvider;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);

        /* first row */
        $lbl = $this->uiFactory->createLabel("unknown map", Label::TYPE_NORMAL, "MapName");
        $lbl->setAlign("center", "center2")->setPosition(30, 0);
        $lbl->setTextSize(1)->setSize(60, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0017")->setScriptEvents(true);
        // $manialink->addChild($lbl);

        /* second row */
        $line = $this->uiFactory->createLayoutLine(0, -4.45, [], 0.5);
        $line->setAlign("left", "top");
        $manialink->addChild($line);
        $div = ((60 - 1) / 3);

        $lbl = $this->uiFactory->createLabel("0 / 0", Label::TYPE_NORMAL, "Players");
        $lbl->setTextPrefix("👥  ");
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize($div, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0013")->setScriptEvents(true);
        $lbl->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "callbackShowPlayers"], [],
            true));
        $line->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("0 / 0", Label::TYPE_NORMAL, "Spectators");
        $lbl->setTextPrefix("🎥  ");
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize($div, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0017")->setScriptEvents(true);
        $line->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("", Label::TYPE_NORMAL, "LocalTime");
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize($div, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0017")->setScriptEvents(true);
        $line->addChild($lbl);

        /* third row */
        $line2 = $this->uiFactory->createLayoutLine(0, -9.0, [], 0.5);
        $manialink->addChild($line2);
        $div = ((60 - 1.5) / 4);

        $lbl = $this->uiFactory->createLabel("Recs", Label::TYPE_NORMAL);
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize($div, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0014")->setScriptEvents(true);
        $lbl->setAction($this->actionFactory->createManialinkAction(
            $manialink, [$this, "callbackShowRecs"], [], true)
        );

        $line2->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("Maps", Label::TYPE_NORMAL);
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize($div, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0014")->setScriptEvents(true);
        $lbl->setAction($this->actionFactory->createManialinkAction(
            $manialink, [$this, "callbackShowMapList"], [], true)
        );

        $line2->addChild($lbl);

        $lbl = $this->uiFactory->createLabel("", Label::TYPE_NORMAL);
        $lbl->setTextPrefix("  ");
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize($div, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("0707")->setScriptEvents(true);
        $lbl->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "callbackVoteYes"], [], true));
        $this->lblYes = $lbl;
        $line2->addChild($this->lblYes);

        $lbl = $this->uiFactory->createLabel("", Label::TYPE_NORMAL);
        $lbl->setTextPrefix("  ");
        $lbl->setAlign("center", "center2");
        $lbl->setTextSize(1)->setSize($div, 4);
        $lbl->setAreaColor("0017")->setAreaFocusColor("7007")->setScriptEvents(true);
        $lbl->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "callbackVoteNo"], [], true));
        $this->lblNo = $lbl;
        $line2->addChild($this->lblNo);


        $playersMax = $ladderMax = $this->gameDataStorage->getServerOptions()->currentMaxPlayers;
        $spectatorMax = $ladderMax = $this->gameDataStorage->getServerOptions()->currentMaxSpectators;
        $manialink->getFmlManialink()->getScript()->addScriptFunction("",
            <<<EOL
               Void updatePlayers() {
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


        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
            
           if (AllPlayerCount != Players.count) {
               AllPlayerCount = Players.count;
               updatePlayers();
           }
           
           if (OldDateText != CurrentLocalDateText) {
                OldDateText = CurrentLocalDateText;
                declare Text Seconds = TextLib::SubString(CurrentLocalDateText,17,2);
                declare Text delim = ":";          
                Counter = (Counter+1)%2;
                if (Counter == 1) {
                    delim = ".";
                }

                declare Hours = TextLib::SubString(CurrentLocalDateText,10,3);
                declare Minutes = TextLib::SubString(CurrentLocalDateText,14,2);
                LocalTime.Value = "🕑 "^Hours^delim^Minutes;                                                
           } 
           
           
           
           
    
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit,
            <<<EOL
            declare Integer AllPlayerCount = -1;
            declare Text OldDateText = "";
            declare Counter = 0;                                         
           // (Page.GetFirstChild("MapName") as CMlLabel).Value = Map.AuthorNickName ^ "\$z\$s - " ^ Map.MapName;                                         
            declare LocalTime = (Page.GetFirstChild("LocalTime") as CMlLabel);         
            updatePlayers();                                                                                     
EOL
        );

        $manialink->addChild($line);

    }

    /**
     * @param ManialinkInterface|Widget $manialink
     * @param string                    $login
     * @param array                     $entries
     * @param array                     $args
     */
    public function callbackVoteYes(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->mapRatingsService->changeRating($login, 1);
    }

    /**
     * @param ManialinkInterface|Widget $manialink
     * @param string                    $login
     * @param array                     $entries
     * @param array                     $args
     */
    public function callbackVoteNo(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->mapRatingsService->changeRating($login, -1);
    }

    public function callbackShowMapList(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, "/maps", true);
    }

    public function callbackShowRecs(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, "/recs", true);
    }

    public function callbackShowPlayers(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, "/players", true);
    }


    /** @param Maprating[] $ratings */
    public function setMapRatings($ratings)
    {
        $yes = 0;
        $no = 0;
        foreach ($ratings as $login => $rating) {
            $score = $rating->getScore();

            if ($score === 1) {
                $yes++;
            }
            if ($score === -1) {
                $no++;
            }
        }

        $this->lblYes->setText($yes.' $cbb/ '.count($ratings));
        $this->lblNo->setText($no.' $cbb/ '.count($ratings));
    }

}
