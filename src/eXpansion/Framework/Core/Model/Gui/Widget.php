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
    private $_hideable = true;

    /**
     * Widget constructor.
     *
     * @param ManialinkFactoryInterface $manialinkFactory
     * @param Group                     $group
     * @param Translations              $translationHelper
     * @param int                       $name
     * @param int                       $sizeX
     * @param float|null                $sizeY
     * @param null                      $posX
     * @param null                      $posY
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

        $widgetFrameFactory->build($this, $this->windowFrame, $name, $sizeX, $sizeY, $this->_hideable);
    }


    /**
     * set if widget can be hided with f9
     * @param $status
     */
    public function setWidgetHide($status)
    {
        $this->_hideable = $status;
    }

}
