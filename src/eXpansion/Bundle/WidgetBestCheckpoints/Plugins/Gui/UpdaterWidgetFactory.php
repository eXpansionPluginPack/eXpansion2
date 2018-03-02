<?php

namespace eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ScriptVariableUpdateFactory;
use FML\Script\Builder;

class UpdaterWidgetFactory extends ScriptVariableUpdateFactory
{
    protected $playerGroup;

    public function __construct(
        $name,
        array $variables,
        float $maxUpdateFrequency = 0.5,
        WidgetFactoryContext $context,
        Group $playerGroup
    ) {
        parent::__construct($name, $variables, $maxUpdateFrequency, $context);
        $this->playerGroup = $playerGroup;
    }

    /**
     * Update with new local record.
     *
     * @param array $checkpoints
     */
    public function setLocalRecord($checkpoints)
    {
        if (count($checkpoints) > 0) {
            $this->updateValue($this->playerGroup, 'LocalRecordCheckpoints', Builder::getArray($checkpoints, true));
        } else {
            $this->updateValue($this->playerGroup, 'LocalRecordCheckpoints', "Integer[Integer]");
        }
    }

    public function setDedimaniaRecord($checkpoints)
    {
        if (count($checkpoints) > 0) {
            $this->updateValue($this->playerGroup, 'DedimaniaCheckpoints', Builder::getArray($checkpoints, true));
        } else {
            $this->updateValue($this->playerGroup, 'DedimaniaCheckpoints', "Integer[Integer]");
        }
    }

}
