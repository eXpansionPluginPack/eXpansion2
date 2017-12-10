<?php

namespace eXpansion\Bundle\LocalMapRatings\Plugin\Gui;

use eXpansion\Bundle\LocalMapRatings\Services\MapRatingsService;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Gui\Builders\WidgetBackground;

class MapRatingsWidget extends WidgetFactory
{
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






        $bg = new WidgetBackground($this->sizeX, $this->sizeY);
        $manialink->addChild($bg);
    }


    protected function updateContent(ManialinkInterface $manialink)
    {


    }


}
