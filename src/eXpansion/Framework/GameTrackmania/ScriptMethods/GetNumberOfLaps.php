<?php

namespace eXpansion\Framework\GameTrackmania\ScriptMethods;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\ScriptMethods\AbstractScriptMethod;

/**
 * Class GetNumberOfLaps
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\GameManiaplanet\ScriptMethods
 */
class GetNumberOfLaps extends AbstractScriptMethod implements ListenerInterfaceExpTimer
{
    /**
     * @inheritdoc
     */
    public function onPreLoop()
    {
        $this->currentData = null;
    }

    /**
     * @inheritdoc
     */
    public function onPostLoop()
    {
        // Nothing.
    }

    /**
     * @inheritdoc
     */
    public function onEverySecond()
    {
        // Nothing.
    }
}
