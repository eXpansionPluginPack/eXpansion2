<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WidgetFrameFactoryInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Elements\Dico;
use FML\Elements\Format;
use FML\Script\Features\ToggleInterface;
use FML\Types\Container;
use FML\Types\Renderable;

/**
 * Class Widget is a specific type of FmlManialink.
 *
 * @package eXpansion\Framework\Core\Model\Gui
 */
class Widget extends FmlManialink implements Container
{
    /**
     * Widget constructor.
     *
     * @param ManialinkFactoryInterface $manialinkFactory
     * @param Group $group
     * @param Translations $translationHelper
     * @param int $name
     * @param int $sizeX
     * @param float|null $sizeY
     * @param null $posX
     * @param null $posY
     */
    public function __construct(
        ManialinkFactoryInterface $manialinkFactory,
        Group $group,
        Translations $translationHelper,
        WidgetFrameFactoryInterface $widgetFrameFactory,
        $name,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null
    ) {
        parent::__construct($manialinkFactory, $group, $translationHelper, $name, $sizeX, $sizeY, $posX, $posY);

        $widgetFrameFactory->build($this, $this->windowFrame, $name, $sizeX, $sizeY);
    }
}
