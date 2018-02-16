<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetLabel;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Quad;
use FML\Script\ScriptLabel;

class CustomScoreboardWidget extends WidgetFactory
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

        $manialink->getFmlManialink()->setLayer("ScoresTable");

        $divider = $this->uiFactory->createLine(0, -3)->setLength(40)->setStroke(0.33)->setColor("fff");

        $frame = Frame::create()->setPosition(-50, 60);
        $frame->addChildren([
            $this->uiFactory->createLabel("Live Rankings", uiLabel::TYPE_TITLE)->setTextSize(3)
                ->setVerticalAlign("center2"),
            $divider,
        ]);
        $manialink->addChild($frame);

        $x = 0;
        $layout = $this->uiFactory->createLayoutLine(0, -6, [], 4);
        $layout->setId("PlayerFrame");
        for ($i = 0; $i < 2; $i++) {
            $column = $this->uiFactory->createLayoutRow(0, 0, [], -0.5);
            for ($j = 0; $j < 20; $j++) {
                $line = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);
                $line->setId("player_$x");
                $line->addClass("PlayerItem");

                $place = new WidgetLabel(($x + 1).".");
                $place->setAlign("center", "center2")->setSize(6, 3);

                $flag = Quad::create();
                $flag->setImageUrl("file://Media/Flags/FIN.dds");
                $flag->setAlign("left", "center2")->setSize(3, 3);

                $nick = new WidgetLabel("player");
                $nick->setTextPrefix(" ");
                $nick->setTextFont("BiryaniDemiBold");
                $nick->setAlign("left", "center2")->setSize(30, 3);

                $status = new WidgetLabel('$999afk $fff📷');
                $status->setTextPrefix(" ");
                $status->setTextFont("BiryaniDemiBold");
                $status->setAlign("center", "center2")->setSize(6, 3);

                $score = new WidgetLabel("0:00.000");
                $score->setTextFont("BiryaniDemiBold");
                $score->setAlign("center", "center2")->setSize(12, 3);

                $line->addChildren([
                    $place,
                    $nick,
                    $flag,
                    $status,
                    $score,
                ]);

                $column->addChild($line);
                $x++;
            }
            $layout->addChild($column);
        }
        $frame->addChild($layout);
        $divider->setLength($layout->getWidth());
        $frame->setX(-($layout->getWidth() / 2));
        $manialink->getFmlManialink()->getScript()->addScriptFunction("TimeToText", <<<EOL
        
            Text FormatSec(Real sec, Text color, Text highlite) {
                if (sec > 10.) {
                    return highlite ^ TextLib::FormatReal(sec,3,False,False);
                } 
                return color ^ 0 ^ highlite ^ TextLib::FormatReal(sec,3,False,False);                                
            }
            
            Text TimeToText(Integer intime) {
                declare Text highlite = "\$eff";
                declare Text color = "\$bcc";
                if (intime == -1) {
                    return color ^ "-:--.---";
                }
                
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
            
            Void updateScoreTable() {
                Page.GetClassChildren ("PlayerItem", Page.MainFrame, True);
                declare CTmMlPlayer[Text] PlayersByLogin = CTmMlPlayer[Text];
                foreach (Player in Players) {
                    PlayersByLogin[Player.User.Login] <=> Player;
                }
               
                foreach (key => Item in Page.GetClassChildren_Result) {
                    declare Frame <=> (Item as CMlFrame);
                    if (Scores.existskey(key)) {
                        Frame.Show();
                        declare Color = <0., 0., 0.>;               
                        declare Text Fame = "";
                        declare Text Status = "";
                        declare Text Login = Scores[key].User.Login;
                        if (Scores[key].User.FameStars > 0) {
                            Fame = " \$z\$ff0★\$z ";
                        } 
                        if (PlayersByLogin.existskey(Login)) {
                            if (PlayersByLogin[Login].RequestsSpectate) {
                                Status ^= "\$fff📷";
                            }
                        } else {
                             Status ^= "\$999✖";
                        }
                        if (Login == InputPlayer.User.Login) {
                          Color = <0.3, 0.85, .1>;  
                        } 
                        (Frame.Controls[1] as CMlLabel).Value =  Fame ^ Scores[key].User.Name;           
                        (Frame.Controls[2] as CMlQuad).ImageUrl = Scores[key].User.CountryFlagUrl;
                        (Frame.Controls[3] as CMlLabel).Value = Status;
                        (Frame.Controls[4] as CMlLabel).Value = TimeToText(Scores[key].BestLap.Time);
                    } else {
                        Frame.Hide();
                    }
                }
            } 

EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
          declare Text oldTime = "";      
EOL
        );

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop, <<<EOL
              if (CurrentLocalDateText != oldTime) {
                 updateScoreTable();
              }            
EOL
        );


    }


}
