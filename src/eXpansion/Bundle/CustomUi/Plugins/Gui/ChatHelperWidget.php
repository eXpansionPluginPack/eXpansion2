<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use FML\Script\ScriptLabel;

class ChatHelperWidget extends WidgetFactory
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * ChatHelperWidget constructor.
     * @param                      $name
     * @param                      $sizeX
     * @param                      $sizeY
     * @param                      $posX
     * @param                      $posY
     * @param WidgetFactoryContext $context
     * @param Dispatcher           $dispatcher
     * @param PlayerStorage        $playerStorage
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WidgetFactoryContext $context,
        Dispatcher $dispatcher,
        PlayerStorage $playerStorage
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->dispatcher = $dispatcher;
        $this->playerStorage = $playerStorage;
    }


    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $entry = $this->uiFactory->createInput("publicChat", "", 90);
        $action = $this->actionFactory->createManialinkAction($manialink, [$this, "onChat"], []);

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::EntrySubmit, <<<eol
            if (Event.ControlId == "publicChat" ) {              
                declare CMlEntry Entry <=> (Page.GetFirstChild("publicChat") as CMlEntry);
                Entry.SetText(" ", False);  
            }
eol
        );


        $entry->setAction($action);
        $manialink->addChild($entry);

    }

    public function onChat($manialink, $login, $entries, $args)
    {
        $player = $this->playerStorage->getPlayerInfo($login);
        if (!empty($entries['publicChat'])) {
            $this->dispatcher->dispatch("PlayerChat", [
                $player->getPlayerId(),
                $login,
                $entries['publicChat'],
            ]);
        }
    }

}
