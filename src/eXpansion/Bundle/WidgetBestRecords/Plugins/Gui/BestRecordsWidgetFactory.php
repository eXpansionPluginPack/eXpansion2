<?php

namespace eXpansion\Bundle\WidgetBestRecords\Plugins\Gui;

use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\uiLabel;

class BestRecordsWidgetFactory extends WidgetFactory
{
    /** @var string */
    private $authorName;
    /** @var int */
    private $authorTime;

    /** @var UiLabel */
    private $lblDediNick;
    /** @var UiLabel */
    private $lblDediTime;

    /** @var UiLabel */
    private $lblAuthorNick;
    /** @var UiLabel */
    private $lblAuthorTime;

    /** @var UiLabel */
    private $lblLocalNick;
    /** @var UiLabel */
    private $lblLocalTime;
    /**
     * @var Time
     */
    private $time;


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
        Time $time

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->time = $time;
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

        $line = $this->uiFactory->createLayoutLine(0, 0, [], 1);
        $lbl = $this->createLabel("Author", "0017")->setSize(15, 4);
        $line->addChild($lbl);

        $this->lblAuthorNick = $this->createLabel("n/a", "0023")->setSize(25, 4);
        $line->addChild($this->lblAuthorNick);

        $this->lblAuthorTime = $this->createLabel("-:--:---", "0015")->setSize(12, 4);

        $line->addChild($this->lblAuthorTime);
        $manialink->addChild($line);

        $line2 = $this->uiFactory->createLayoutLine(0, -5, [], 1);

        $lbl = $this->createLabel("Record", "0017")->setSize(15, 4);
        $line2->addChild($lbl);

        $this->lblLocalNick = $this->createLabel("n/a", "0023")->setSize(25, 4);
        $line2->addChild($this->lblLocalNick);

        $this->lblLocalTime = $this->createLabel("-:--:---", "0015")->setSize(12, 4);
        $line2->addChild($this->lblLocalTime);
        $manialink->addChild($line2);

        $line3 = $this->uiFactory->createLayoutLine(0, -10, [], 1);

        $lbl = $this->createLabel("Dedimania", "0017")->setSize(15, 4);
        $line3->addChild($lbl);

        $this->lblDediNick = $this->createLabel("n/a", "0023")->setSize(25, 4);
        $line3->addChild($this->lblDediNick);

        $this->lblDediTime = $this->createLabel("-:--:---", "0015")->setSize(12, 4);
        $line3->addChild($this->lblDediTime);
        $manialink->addChild($line3);
    }

    private function createLabel($text, $color)
    {
        return $this->uiFactory->createLabel($text, UiLabel::TYPE_NORMAL)->setTranslate(false)
            ->setAlign("left", "center2")->setTextSize(1)->setScriptEvents(true)
            ->setAreaColor($color)->setAreaFocusColor($color)->setTextColor("eff")->setTextPrefix(" ");
    }


    public function setLocalRecord($record)
    {
        if ($record instanceof Record) {
            try {
                $this->lblLocalNick->setText($record->getPlayer()->getNicknameStripped());
                $this->lblLocalTime->setText($this->time->timeToText($record->getScore(), true));
            } catch (\Exception $e) {

            }
        } else {
            $this->lblLocalNick->setText("");
            $this->lblLocalTime->setText("-:--.---");
        }
    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        $this->lblAuthorTime->setText($this->time->timeToText($this->authorTime, true));
        $this->lblAuthorNick->setText($this->authorName);
    }


}
