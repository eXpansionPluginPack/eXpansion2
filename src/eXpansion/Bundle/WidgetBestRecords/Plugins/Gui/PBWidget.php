<?php

namespace eXpansion\Bundle\WidgetBestRecords\Plugins\Gui;

use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\FmlManialinkFactoryContext;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Plugins\Gui\FmlManialinkFactory;
use eXpansion\Framework\Gui\Components\Label;

class PBWidget extends FmlManialinkFactory
{
    /** @var int */
    private $authorTime;

    /** @var Time */
    private $time;

    /** @var Label */
    private $lblPB;

    /** @var Label */
    private $lblPBTime;
    /** @var */
    private $lblAuthorTime;

    private $timesByLogin = [];

    /***
     * MenuFactory constructor.
     *
     * @param                            $name
     * @param                            $sizeX
     * @param                            $sizeY
     * @param null                       $posX
     * @param null                       $posY
     * @param FmlManialinkFactoryContext $context
     * @param Time                       $time
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        FmlManialinkFactoryContext $context,
        Time $time

    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->time = $time;

    }

    /**
     * @param int $authorTime
     */
    public function setAuthorTime(int $authorTime)
    {
        $this->authorTime = $authorTime;
    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $manialink->setData("authorTime", 0);
        $manialink->getFmlManialink()->setScript(null);

        $line = $this->uiFactory->createLayoutLine(0, 0, [], 0.5);

        $lbl = $this->createLabel("Author", "0017")->setSize(14.85, 4);
        $line->addChild($lbl);

        $this->lblAuthorTime = $this->createLabel("-:--:---", "0023")->setSize(14.85, 4);
        $line->addChild($this->lblAuthorTime);

        $this->lblPB = $this->createLabel("PB", "0017")->setSize(14.85, 4);
        $line->addChild($this->lblPB);

        $this->lblPBTime = $this->createLabel("-:--:---", "0023")->setSize(14.85, 4);
        $line->addChild($this->lblPBTime);

        $manialink->addChild($line);
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
     * @param ManialinkInterface $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
        if ($this->authorTime) {
            $this->lblAuthorTime->setText($this->time->timeToText($this->authorTime, true));
        } else {
            $this->lblAuthorTime->setText("-:--:---");
        }

        $recipient = $manialink->getUserGroup()->getLogins();

        if (count($recipient) == 1) {
            $login = $recipient[0];
            if (isset($this->timesByLogin[$login])) {
                $this->lblPBTime->setText($this->time->timeToText($this->timesByLogin[$login], true));
            }
        } else {
            $this->lblPBTime->setText("-:--:---");
        }
    }

    /**
     * @param          $login
     * @param int|null $time
     */
    public function setPB($login, $time)
    {
        $this->timesByLogin[$login] = $time;
    }

    public function reset()
    {
        $this->timesByLogin = [];

    }

}
