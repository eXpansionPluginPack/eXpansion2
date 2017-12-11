<?php

namespace eXpansion\Bundle\LocalMapRatings\Plugin\Gui;

use eXpansion\Bundle\LocalMapRatings\Services\MapRatingsService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Components\uiLabel;

class MapRatingsWidget extends WidgetFactory
{

    /** @var uiLabel */
    private $lblRatings;
    /**
     * @var MapRatingsService
     */
    private $mapRatingsService;

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

        $this->lblRatings = $this->uiFactory->createLabel("", uiLabel::TYPE_NORMAL);
        $this->lblRatings->setPosition(0, 0)->setSize(40, 6)
            ->setTextSize(2)->setAlign("right", "top");
        $manialink->addChild($this->lblRatings);

    }


    protected function updateContent(ManialinkInterface $manialink)
    {
        $ratings = $this->mapRatingsService->getRatingsPerPlayer();
        $total = count($ratings);
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

        $this->lblRatings->setText('$0d0 $fff'.$yes.'   $d00 $fff'.$no." 👥 ".$total);
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

}
