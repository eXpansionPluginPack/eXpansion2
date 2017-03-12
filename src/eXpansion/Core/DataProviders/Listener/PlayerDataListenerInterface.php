<?php
namespace eXpansion\Core\DataProviders\Listener;

use eXpansion\Core\DataProviders\PlayerDataProvider;
use eXpansion\Core\Storage\Data\Player;

/**
 * Interface PlayerDataListenerInterface for plugins using the PlayerDataProvider data provider.
 * @see PlayerDataProvider
 */
interface PlayerDataListenerInterface
{
    public function onPlayerConnect(Player $player);
}