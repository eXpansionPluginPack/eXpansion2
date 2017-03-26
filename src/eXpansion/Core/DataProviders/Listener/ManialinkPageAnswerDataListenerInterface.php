<?php

namespace eXpansion\Core\DataProviders\Listener;

interface ManialinkPageAnswerDataListenerInterface
{
    /**
     * When a player uses an action dispatch information.
     *
     * @param $login
     * @param $actionId
     * @param array $entryValues
     *
     */
    public function onPlayerManialinkPageAnswer($login, $actionId, array $entryValues);
}