<?php
namespace eXpansion\Framework\Core\DataProviders\Listener;

use eXpansion\Framework\Core\DataProviders\PlayerDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;

/**
 * Interface PlayerDataListenerInterface for plugins using the PlayerDataProvider data provider.
 * @see PlayerDataProvider
 */
interface PlayerDataListenerInterface
{
    public function onPlayerConnect(Player $player);

    public function onPlayerDisconnect(Player $player, $disconnectionReason);

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player);

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player);
}