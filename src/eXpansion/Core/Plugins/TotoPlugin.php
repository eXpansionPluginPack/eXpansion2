<?php

namespace eXpansion\Core\Plugins;

use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Plugins\Gui\GroupManialinkFactory;
use eXpansion\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Core\Services\Console;
use eXpansion\Core\Storage\Data\Player;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Core\Plugins
 */
class TotoPlugin implements ChatDataListenerInterface, StatusAwarePluginInterface
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
        $from = trim($player->getNickName());
        if ($player->getPlayerId() === 0) {
            $from = '$777Console';
        }

        $this->console->writeln($from . $text);
    }


    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->mlFactory->create($this->playersGroup);
        } else {
            $this->mlFactory->destroy($this->playersGroup);
        }
    }
}
