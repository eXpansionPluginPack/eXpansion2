<?php

namespace eXpansion\Framework\GameManiaplanet\ScriptMethods;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\ScriptMethods\AbstractScriptMethod;

/**
 * Class GetScores
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\GameManiaplanet\ScriptMethods
 */
class GetScores extends AbstractScriptMethod implements ListenerInterfaceExpTimer
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
