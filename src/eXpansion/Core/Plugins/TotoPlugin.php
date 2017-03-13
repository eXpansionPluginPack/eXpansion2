<?php

namespace eXpansion\Core\Plugins;

use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Core\Storage\Data\Player;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Core\Plugins
 */
class TotoPlugin implements ChatDataListenerInterface
{
    public function onPlayerChat(Player $player, $text) {
        echo "[{$player->getNickName()}]$text\n";
    }
}