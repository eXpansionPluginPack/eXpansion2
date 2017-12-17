<?php

namespace eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Widget;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WidgetFactory;
use FML\Script\Builder;
use FML\Script\Script;
use FML\Script\ScriptLabel;

class UpdaterWidgetFactory extends WidgetFactory
{
    private $localRecordCheckpoints = "Integer[Integer]";

    /***
     * MenuFactory constructor.
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
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

    }

    /**
     * @param Widget|ManialinkInterface $manialink
     */
    protected function createContent(ManialinkInterface $manialink)
    {

    }

    /**
     * @param ManialinkInterface|Widget $manialink
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        $checkpoints = $this->localRecordCheckpoints;
        $id = uniqid("exp_");

        $manialink->getFmlManialink()->setScript(new Script());
        $manialink->getFmlManialink()->getScript()->addCustomScriptLabel(ScriptLabel::OnInit, <<<EOL
            declare Integer[Integer] BestCp_LocalRecordCheckpoints for LocalUser = Integer[Integer];
            declare Text BestCp_LocalRecord_Check for LocalUser = "";
            BestCp_LocalRecord_Check = "$id";
            BestCp_LocalRecordCheckpoints = $checkpoints;            
EOL
        );


    }

    public function setLocalRecord($checkpoints)
    {

        if (count($checkpoints) > 0) {
            $this->localRecordCheckpoints = Builder::getArray($checkpoints, true);
        } else {
            $this->localRecordCheckpoints = "Integer[Integer]";
        }
    }


}
