<?php

namespace eXpansion\Framework\Core\Plugins\Gui;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerExpWidgetPosition;
use eXpansion\Framework\Core\Model\Gui\Factory\WidgetFrameFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\FmlManialink;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\UserGroups\Group;

/**
 * Class ManialiveFactory allow the creation of manialinks.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class WidgetFactory extends FmlManialinkFactory implements ListenerExpWidgetPosition
{
    /** @var WidgetFrameFactoryInterface */
    protected $widgetFrameFactory;

    /** @var float */
    protected $defaultPositionX;

    /** @var float */
    protected $defaultPositionY;

    /**
     * WidgetFactory constructor.
     *
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param null                 $posX
     * @param null                 $posY
     * @param WidgetFactoryContext $context
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context
    ) {
        parent::__construct(
            $name,
            $sizeX,
            $sizeY,
            $posX,
            $posY,
            $context
        );

        $this->translationsHelper = $context->getTranslationsHelper();
        $this->uiFactory = $context->getUiFactory();
        $this->widgetFrameFactory = $context->getWidgetFrameFactory();

        $this->defaultPositionX = $posX;
        $this->defaultPositionY = $posY;
    }

    /**
     * @param Group $group
     *
     * @return Window
     */
    protected function createManialink(Group $group, $hideable = true)
    {

        $className = $this->className;
        $manialink = new $className(
            $this,
            $group,
            $this->translationsHelper,
            $this->widgetFrameFactory,
            $this->name,
            $this->sizeX,
            $this->sizeY,
            $this->posX,
            $this->posY,
            $hideable
        );

        return $manialink;
    }

    /**
     * @inheritdoc
     */
    public function updateOptions($posX, $posY, $options)
    {
        $posX = !is_null($posX) ? $posX : $this->defaultPositionX;
        $posY = !is_null($posY) ? $posY : $this->defaultPositionY;

        // Check if changed.
        if ($this->posX == $posX && $this->posY == $posY) {
            return;
        }

        // Update position for future widgets.
        $this->posX = $posX;
        $this->posY = $posY;

        // Update current widgets.
       foreach ($this->guiHandler->getFactoryManialinks($this) as $manialink) {
           if ($manialink instanceof FmlManialink) {
               $manialink->setPosition($this->posX, $this->posY);
           }

           $this->update($manialink->getUserGroup());
       }
    }
}
