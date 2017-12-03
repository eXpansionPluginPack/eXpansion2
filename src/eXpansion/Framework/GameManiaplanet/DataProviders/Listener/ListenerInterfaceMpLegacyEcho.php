<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use eXpansion\Framework\GameManiaplanet\DataProviders\EchoDataProvider;

/**
 * Interface for plugins using the EchoDataProvider.
 *
 * @see EchoDataProvider
 * @author Reaby
 */
interface ListenerInterfaceMpLegacyEcho
{
    /**
     * @param string $internal
     * @param string $public
     * @return void
     */
    public function onEcho($internal, $public);
}
