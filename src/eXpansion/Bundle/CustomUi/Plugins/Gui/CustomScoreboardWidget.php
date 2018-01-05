<?php

namespace eXpansion\Bundle\CustomUi\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use FML\Script\ScriptLabel;

class CustomScoreboardWidget extends WidgetFactory
{

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
        WidgetFactoryContext $context
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
    }


    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
            ClientUI.ScoreTableOnlyManialink = True;            
            ClientUI.ScoreTable = """
            <?xml version="1.0" encoding="utf-8" standalone="yes" ?>
            <manialink version="3">
                <quad pos="0 50" z-index="0" size="140 20" bgcolor="FFFA" halign="center"/>
            </manialink>            
            """;
                        
EOL
        );




    }


}
