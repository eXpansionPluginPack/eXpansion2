<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Factory\WidgetFrameFactoryInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Types\Container;

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
     * @param Group                     $group
     * @param Translations              $translationHelper
     * @param string                    $name
     * @param float|null                $sizeX
     * @param float|null                $sizeY
     * @param float|null                $posX
     * @param float|null                $posY
     * @param bool                      $hideable
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
        $posY = null,
        $hideable = true
    ) {
        parent::__construct($manialinkFactory, $group, $translationHelper, $name, $sizeX, $sizeY, $posX, $posY);
        $widgetFrameFactory->build($this, $this->windowFrame, $name, $sizeX, $sizeY, $hideable);
    }
}
