<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\PlayerDataProvider;

/**
 * Interface PlayerDataListenerInterface for plugins using the PlayerDataProvider data provider.
 * @see PlayerDataProvider
 */
interface ListenerInterfaceMpLegacyPlayer
{
    /**
     * @param Player $player
     * @return void
     */
    public function onPlayerConnect(Player $player);

    /**
     * @param Player $player
     * @param string $disconnectionReason
     * @return void
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason);

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player);

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player);
}
