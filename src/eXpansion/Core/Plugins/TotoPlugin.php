<?php

namespace eXpansion\Core\Plugins;

use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Core\Services\Console;
use eXpansion\Core\Storage\Data\Player;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Core\Plugins
 */
class TotoPlugin implements ChatDataListenerInterface
{
    /** @var Console  */
    protected $console;

    /** @var ManialinkFactory */
    protected $mlFactory;

    /** @var Group  */
    protected $playersGroup;

    function __construct(
        Console $console,
        ManialinkFactory $mlFactory,
        Group $players
    )
    {
        $this->console = $console;
        $this->mlFactory = $mlFactory;
        $this->playersGroup = $players;
    }

    public function onPlayerChat(Player $player, $text)
    {
        $text = trim($text);
        $this->console->writeln('$ff0['.trim($player->getNickName()).'$ff0] '.$text);

        $this->mlFactory->create($this->playersGroup);
    }
}
