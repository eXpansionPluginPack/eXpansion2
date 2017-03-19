<?php

namespace eXpansion\Core\Plugins;

use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Core\Services\Console;
use eXpansion\Core\Storage\Data\Player;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Core\Plugins
 */
class TotoPlugin implements ChatDataListenerInterface
{

    public $console;

    function __construct(Console $console)
    {
        $this->console = $console;
    }

    public function onPlayerChat(Player $player, $text)
    {
        $text = trim($text);
        $from = trim($player->getNickName());
        if ($player->getPlayerId() === 0) {
            $from = '$777Console';
        }
        $this->console->writeln('$ff0[' . $from . '$ff0] ' . $text);
    }
}
