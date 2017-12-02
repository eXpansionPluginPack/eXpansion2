<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

interface ListenerInterfaceMpLegacyManialink
{
    /**
     * When a player uses an action dispatch information.
     *
     * @param string $login
     * @param string $actionId
     * @param string[] $entryValues
     * @return void
     */
    public function onPlayerManialinkPageAnswer($login, $actionId, array $entryValues);
}
