<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Bundle\Acme\Plugins\Gui\WindowFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Notifications\Services\Notifications;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Framework\Core\Plugins
 */
class TotoPlugin implements ListenerInterfaceExpApplication, StatusAwarePluginInterface, ListenerInterfaceMpLegacyPlayer
{
    /** @var Console */
    protected $console;

    /** @var WindowFactory */
    protected $mlFactory;

    /** @var Group */
    protected $playersGroup;
    /**
     * @var Notifications
     */
    private $notifications;

    /**
     * TotoPlugin constructor.
     * @param Group         $players
     * @param Console       $console
     * @param Notifications $notifications
     */
    function __construct(
        Group $players,
        Console $console,
        Notifications $notifications
    ) {
        $this->console = $console;
        $this->playersGroup = $players;
        $this->notifications = $notifications;
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

    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // do nothing
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        $this->notifications->notice("eXpansion2 Started Successfully!");
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // do nothing
    }

    /**
     * @param Player $player
     * @return void
     */
    public function onPlayerConnect(Player $player)
    {
        $this->notifications->info($player->getNickName().'$z$s'." Joins.");
    }

    /**
     * @param Player $player
     * @param string $disconnectionReason
     * @return void
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        $this->notifications->info($player->getNickName().'$z$s'." Leaves.");
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerInfoChanged() method.
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerAlliesChanged() method.
    }
}
