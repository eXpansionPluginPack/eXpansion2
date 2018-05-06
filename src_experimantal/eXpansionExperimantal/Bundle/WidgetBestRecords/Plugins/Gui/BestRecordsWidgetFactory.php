<?php

namespace eXpansionExperimantal\Bundle\WidgetBestRecords\Plugins\Gui;

use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Gui\Components\Label;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaRecord;

class BestRecordsWidgetFactory extends WidgetFactory
{
    /** @var string */
    private $authorName;
    /** @var int */
    private $authorTime;

    /** @var Label */
    private $lblDediNick;
    /** @var Label */
    private $lblDediTime;

    /** @var Label */
    private $lblAuthorNick;
    /** @var Label */
    private $lblAuthorTime;

    /** @var Label */
    private $lblLocalNick;
    /** @var Label */
    private $lblLocalTime;
    /**
     * @var Time
     */
    private $time;

    protected $chatCommandDataProvider;


    /***
     * MenuFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WidgetFactoryContext $context
     * @param Time                 $time
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        Time $time,
        ChatCommandDataProvider $chatCommandDataProvider

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->time = $time;
        $this->chatCommandDataProvider = $chatCommandDataProvider;
    }

    /**
     * @param int $authorTime
     */
    public function setAuthorTime($author, int $authorTime)
    {
        $this->authorName = $author;
        $this->authorTime = $authorTime;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $row = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);
        $manialink->addChild($row);


        $line2 = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);
        $lbl = $this->createLabel("Record", "0017")->setSize(14.5, 4);
        $lbl->setHorizontalAlign("center");
        $lbl->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "callbackShowLocalRecords"], [],
            true));
        $line2->addChild($lbl);


        $this->lblLocalTime = $this->createLabel("-:--:---", "0015")->setSize(14.5, 4);
        $this->lblLocalTime->setHorizontalAlign("center");
        $line2->addChild($this->lblLocalTime);
        $row->addChild($line2);

        $line3 = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);

        $lbl = $this->createLabel("Dedimania", "0017")->setSize(14.5, 4);
        $lbl->setHorizontalAlign("center");
        $lbl->setAction($this->actionFactory->createManialinkAction($manialink, [$this, "callbackShowDedimaniaRecords"],
            [], true));
        $line3->addChild($lbl);


        $this->lblDediTime = $this->createLabel("-:--:---", "0015")->setSize(14.5, 4);
        $this->lblDediTime->setHorizontalAlign("center");
        $line3->addChild($this->lblDediTime);
        $row->addChild($line3);
    }

    /**
     * @param string $text
     * @param string $color
     * @return Label
     */
    private function createLabel($text, $color)
    {
        return $this->uiFactory->createLabel($text, Label::TYPE_NORMAL)->setTranslate(false)
            ->setAlign("left", "center2")->setTextSize(1)->setScriptEvents(true)
            ->setAreaColor($color)->setAreaFocusColor($color)->setTextColor("eff")->setTextPrefix(" ");
    }


    /**
     * @param Record|null $record
     */
    public function setLocalRecord($record)
    {
        if ($record instanceof Record) {
            try {
                $this->lblLocalTime->setText($this->time->timeToText($record->getScore(), true));
            } catch (\Exception $e) {

            }
        } else {
            $this->lblLocalTime->setText("-:--.---");
        }
    }

    /**
     * @param DedimaniaRecord|null $record
     */
    public function setDedimaniaRecord($record)
    {
        if ($record instanceof DedimaniaRecord) {
            try {
                $this->lblDediTime->setText($this->time->timeToText($record->best, true));
            } catch (\Exception $e) {
                $this->lblDediTime->setText("-:--.---");
            }
        } else {
            $this->lblDediTime->setText("-:--.---");
        }
    }

    public function callbackShowLocalRecords(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, "/recs", true);
    }

    public function callbackShowDedimaniaRecords(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->chatCommandDataProvider->onPlayerChat($login, $login, "/dedirecs", true);
    }
}
