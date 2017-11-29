<?php

namespace eXpansion\Framework\Core\Plugins\Gui;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\GuiHandler;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;
use FML\Controls\Control;

/**
 * Class ManialiveFactory allow the creation of manialinks.
 *
 * @package eXpansion\Framework\Core\Plugins\Gui
 * @author Oliver de Cramer
 */
class WidgetFactory extends ManialinkFactory
{
    /** @var Translations */
    protected $translationsHelper;

    /** @var \eXpansion\Framework\Gui\Ui\Factory  */
    protected $uiFactory;

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
        // Hack for FML to use default MP alignements.
        Control::clearDefaultAlign();

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
    }

    /**
     * @param Group $group
     *
     * @return Window
     */
    protected function createManialink(Group $group)
    {

        $className = $this->className;
        $manialink = new $className(
            $this,
            $group,
            $this->translationsHelper,
            $this->name,
            $this->sizeX,
            $this->sizeY,
            $this->posX,
            $this->posY
        );

        return $manialink;
    }
}
