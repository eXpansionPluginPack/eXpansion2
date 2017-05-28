<?php

namespace eXpansion\Framework\Core\Plugins\Gui;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManiaScriptFactory;
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
class WidgetFactory extends ManialinkFactory {

    /** @var Translations */
    protected $translationsHelper;

    /**
     * WidgetFactory constructor.
     *
     * @param               $name
     * @param               $sizeX
     * @param               $sizeY
     * @param null          $posX
     * @param null          $posY
     * @param GuiHandler    $guiHandler
     * @param Factory       $groupFactory
     * @param ActionFactory $actionFactory
     * @param Translations  $translationsHelper
     * @param string        $className
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        GuiHandler $guiHandler,
        Factory $groupFactory,
        ActionFactory $actionFactory,
        Translations $translationsHelper,
        $className = Window::class
    ) {
        // Hack for FML to use default MP alignements.
        Control::clearDefaultAlign();

        parent::__construct(
            $name,
            $sizeX,
            $sizeY,
            $posX,
            $posY,
            $guiHandler,
            $groupFactory,
            $actionFactory,
            $className
        );

        $this->translationsHelper = $translationsHelper;
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
