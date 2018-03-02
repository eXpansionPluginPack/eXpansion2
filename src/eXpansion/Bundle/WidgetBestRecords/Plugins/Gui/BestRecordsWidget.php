<?php

namespace eXpansion\Bundle\WidgetBestRecords\Plugins\Gui;

use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\FmlManialinkFactoryContext;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\FmlManialinkFactory;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Gui\Components\Label;

class BestRecordsWidget extends FmlManialinkFactory
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
    /**
     * @var ChatCommandDataProvider
     */
    private $chatCommandDataProvider;


    /***
     * MenuFactory constructor.
     *
     * @param                            $name
     * @param                            $sizeX
     * @param                            $sizeY
     * @param null                       $posX
     * @param null                       $posY
     * @param FmlManialinkFactoryContext $context
     * @param ChatCommandDataProvider    $chatCommandDataProvider
     * @param Time                       $time
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        FmlManialinkFactoryContext $context,
        ChatCommandDataProvider $chatCommandDataProvider,
        Time $time

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->time = $time;
        $this->chatCommandDataProvider = $chatCommandDataProvider;
    }


    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $manialink->getFmlManialink()->setScript(null);

        $line2 = $this->uiFactory->createLayoutLine(0, -4.45, [], 0.5);

        $lbl = $this->createLabel("Record", "0017")->setSize(15, 4);
        $line2->addChild($lbl);

        $this->lblLocalNick = $this->createLabel("n/a", "0023")->setSize(33, 4);
        $line2->addChild($this->lblLocalNick);

        $this->lblLocalTime = $this->createLabel("-:--:---", "0015")->setSize(12, 4);
        $line2->addChild($this->lblLocalTime);
        $manialink->addChild($line2);

        $line3 = $this->uiFactory->createLayoutLine(0, -9.0, [], 0.5);

        $lbl = $this->createLabel("Dedimania", "0017")->setSize(15, 4);
        $line3->addChild($lbl);

        $this->lblDediNick = $this->createLabel("n/a", "0023")->setSize(33, 4);
        $line3->addChild($this->lblDediNick);

        $this->lblDediTime = $this->createLabel("-:--:---", "0015")->setSize(12, 4);
        $line3->addChild($this->lblDediTime);
        $manialink->addChild($line3);
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
                $this->lblLocalNick->setText($record->getPlayer()->getNicknameStripped());
                $this->lblLocalTime->setText($this->time->timeToText($record->getScore(), true));
            } catch (\Exception $e) {
                $this->lblLocalNick->setText("");
                $this->lblLocalTime->setText("-:--.---");
            }
        } else {
            $this->lblLocalNick->setText("");
            $this->lblLocalTime->setText("-:--.---");
        }
    }

    /**
     * @param int|null $time
     */
    public function setPB($time)
    {
        if ($time) {
            try {
                $this->lblPBTime->setText($this->time->timeToText($time, true));
            } catch (\Exception $e) {
                $this->lblPBTime->setText("-:--.---");
            }
        } else {
            $this->lblPBTime->setText("-:--.---");
        }
    }

    /**
     * @param DedimaniaRecord|null $record
     */
    public function setDedimaniaRecord($record)
    {
        if ($record instanceof DedimaniaRecord) {
            try {
                $this->lblDediNick->setText(TMString::trimStyles($record->nickName));
                $this->lblDediTime->setText($this->time->timeToText($record->best, true));
            } catch (\Exception $e) {
                $this->lblDediNick->setText("");
                $this->lblDediTime->setText("-:--.---");
            }
        } else {
            $this->lblDediNick->setText("");
            $this->lblDediTime->setText("-:--.---");
        }
    }
}
