<?php

namespace eXpansion\Bundle\LocalMapRatings\Plugin\Gui;

use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalMapRatings\Services\MapRatingsService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\uiLabel;

class MapRatingsWidget extends WidgetFactory
{

    /** @var uiLabel */
    private $lblRatingsYes;

    /** @var uiLabel */
    private $lblRatingsNo;

    /**
     * @var MapRatingsService
     */
    private $mapRatingsService;

    /** @var Maprating[] */
    private $ratings = [];

    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        MapRatingsService $mapRatingsService
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->mapRatingsService = $mapRatingsService;
    }

    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $line = $this->uiFactory->createLayoutLine(0, 0, [], 2);
        $manialink->addChild($line);

        $this->lblRatingsYes = $this->uiFactory->createLabel("", uiLabel::TYPE_TITLE);
        $this->lblRatingsYes->setTextSize(2)
            ->setSize(7, 4)
            ->setAction($this->actionFactory->createManialinkAction($manialink,
                [$this, "callbackVoteYes"], []));
        $line->addChild($this->lblRatingsYes);

        $this->lblRatingsNo = $this->uiFactory->createLabel("", uiLabel::TYPE_TITLE);
        $this->lblRatingsNo->setTextSize(2)
            ->setSize(7, 4)
            ->setAction($this->actionFactory->createManialinkAction($manialink,
                [$this, "callbackVoteNo"], []));
        $line->addChild($this->lblRatingsNo);


    }


    protected function updateContent(ManialinkInterface $manialink)
    {
        $ratings = $this->ratings;
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

        $this->lblRatingsYes->setText('$0d0 $fff'.$yes);
        $this->lblRatingsNo->setText('$d00 $fff'.$no);
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
        $this->update($manialink->getUserGroup());
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
        $this->update($manialink->getUserGroup());
    }

    /**
     * @param Maprating[] $ratings
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;
    }

}
