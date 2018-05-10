<?php

namespace eXpansionExperimantal\Bundle\WidgetRecords\Plugins\Gui;

use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\Label;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaRecord;
use FML\Script\ScriptLabel;

class RecordsWidgetFactory extends WidgetFactory
{
    /** @var DedimaniaRecord[] */
    private $dediRecords = [];

    /** @var  Record[] */
    private $localRecords = [];
    /**
     * @var Time
     */
    private $time;

    public function __construct($name, $sizeX, $sizeY, $posX, $posY, WidgetFactoryContext $context, Time $time)
    {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->time = $time;
    }

    /** @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
            Exp_Window.Hide();
EOL
        );
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::Loop,
            <<<EOL
              if (GUIPlayer != Null) {
                    if ( GUIPlayer.RaceState == CTmMlPlayer::ERaceState::BeforeStart || GUIPlayer.RaceState == CTmMlPlayer::ERaceState::Finished) {
                        Exp_Window.Show();
                    }
              
              
                    if (GUIPlayer.RaceState == CTmMlPlayer::ERaceState::Running && GUIPlayer.DisplaySpeed != 0) {
                      Exp_Window.Hide();   
                    }
             }
EOL
        );
    }


    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
        $manialink->getContentFrame()->removeAllChildren();

        $row = $this->uiFactory->createLayoutRow(-160, 0, [], 0.5);
        $manialink->addChild($row);
        $row->addChild($this->uiFactory->createWidgetTitle("Local Records", 65, 4));
        $first = 0;
        $x = 0;
        $out = "";
        foreach ($this->localRecords as $record) {
            if ($x == 0) {
                $first = $record->getScore();
                $out = '$0d0'.$this->time->timeToText($record->getScore(), true);
            } else {
                $diff = $this->time->timeToText(abs($first - $record->getScore()), true, false);
                if (substr($diff, 0, 2) == "0:") {
                    $diff = substr($diff, 2);
                }
                $out = '$ff0+'.$diff;
            }
            if ($x > 15) {
                break;
            }
            $line = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);
            $line->addChildren(
                [
                    $this->uiFactory->createLabel(($x + 1).".", Label::TYPE_NORMAL)
                        ->setSize(5, 3)
                        ->setHorizontalAlign("right")
                        ->setTextSize(1)
                        ->setAreaColor("0007"),


                    $this->uiFactory->createLabel(
                        TMString::trimLinks($record->getPlayer()->getNickname()), Label::TYPE_NORMAL)
                        ->setSize(30, 3)
                        ->setTextPrefix(" ")
                        ->setHorizontalAlign("left")
                        ->setTextSize(1)
                        ->setAreaColor("0007"),

                    $this->uiFactory->createLabel($out." ", Label::TYPE_NORMAL)
                        ->setSize(15, 3)
                        ->setHorizontalAlign("right")
                        ->setTextSize(1)
                        ->setAreaColor("0007"),
                ]);

            $row->addChild($line);

            $x++;
        }

        $row = $this->uiFactory->createLayoutRow(-160, -70, [], 0.5);
        $manialink->addChild($row);

        $row->addChild($this->uiFactory->createWidgetTitle("Dedimania", 65, 4));

        $first = 0;
        $x = 0;
        $out = "";
        foreach ($this->dediRecords as $record) {
            if ($x == 0) {
                $first = $record->best;
                $out = '$0d0'.$this->time->timeToText($record->best, true);
            } else {
                $diff = $this->time->timeToText(abs($first - $record->best), true, false);
                if (substr($diff, 0, 2) == "0:") {
                    $diff = substr($diff, 2);
                }
                $out = '$ff0+'.$diff;
            }
            if ($x > 15) {
                break;
            }
            $line = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);
            $line->addChildren(
                [
                    $this->uiFactory->createLabel(($x + 1).".", Label::TYPE_NORMAL)
                        ->setSize(5, 3)
                        ->setHorizontalAlign("right")
                        ->setTextSize(1)
                        ->setAreaColor("0007"),

                    $this->uiFactory->createLabel(
                        TMString::trimLinks($record->nickName, Label::TYPE_NORMAL))
                        ->setSize(30, 3)
                        ->setTextPrefix(" ")
                        ->setHorizontalAlign("left")
                        ->setTextSize(1)
                        ->setAreaColor("0007"),

                    $this->uiFactory->createLabel($out." ", Label::TYPE_NORMAL)
                        ->setSize(15, 3)
                        ->setHorizontalAlign("right")
                        ->setTextSize(1)
                        ->setAreaColor("0007"),
                ]);

            $row->addChild($line);
            $x++;
        }

    }

    /**
     *
     * @param DedimaniaRecord[] $records
     */
    public function setDedimaniaRecords($records)
    {
        $this->dediRecords = $records;

    }

    /**
     *
     * @param Record[] $records
     */
    public function setLocalRecords($records)
    {
        $this->localRecords = $records;
    }


}
