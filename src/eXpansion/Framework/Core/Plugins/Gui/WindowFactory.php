<?php

namespace eXpansion\Framework\Core\Plugins\Gui;
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
class WindowFactory extends ManialinkFactory {

    protected $windowManiaScriptFactory;

    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        GuiHandler $guiHandler,
        Factory $groupFactory,
        ActionFactory $actionFactory,
        ManiaScriptFactory $windowManiaScriptFactory,
        $className = Window::class
    ) {
        // Hack for FML to use default MP alignements.
        Control::clearDefaultAlign();

        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $guiHandler, $groupFactory, $actionFactory, $className);

        $this->windowManiaScriptFactory = $windowManiaScriptFactory;
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
            $this->windowManiaScriptFactory,
            $this->name,
            $this->sizeX,
            $this->sizeY,
            $this->posX,
            $this->posY
        );

        $actionId = $this->actionFactory->createManialinkAction(
            $manialink,
            [$this, 'closeManialink'],
            ['manialink' => $manialink]
        );

        $manialink->setCloseAction($actionId);

        return $manialink;
    }

    public function closeManialink($login, $answerValues, $arguments)
    {
        /** @var Manialink $manialink */
        $manialink = $arguments['manialink'];
        $this->destroy($manialink->getUserGroup());
    }
}
