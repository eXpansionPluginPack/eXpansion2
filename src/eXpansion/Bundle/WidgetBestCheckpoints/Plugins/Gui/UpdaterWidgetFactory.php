<?php

namespace eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui;

use eXpansion\Framework\Core\Plugins\Gui\ScriptVariableUpdateFactory;
use FML\Script\Builder;

class UpdaterWidgetFactory extends ScriptVariableUpdateFactory
{
    /**
     * Update with new local record.
     *
     * @param array $checkpoints
     */
    public function setLocalRecord($checkpoints)
    {
        if (count($checkpoints) > 0) {
            $this->updateValue('LocalRecordCheckpoints', Builder::getArray($checkpoints, true));
        } else {
            $this->updateValue('LocalRecordCheckpoints', "Integer[Integer]");
        }
    }
}
