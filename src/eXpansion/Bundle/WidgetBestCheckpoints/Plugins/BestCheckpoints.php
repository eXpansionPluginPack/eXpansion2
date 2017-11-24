<?php

namespace eXpansion\Bundle\WidgetBestCheckpoints\Plugins;

use eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui\BestCheckpointsWidgetFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;


class BestCheckpoints implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyPlayer
{
    /** @var Connection */
    protected $connection;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var BestCheckpointsWidgetFactory
     */
    private $widget;
    /**
     * @var Group
     */
    private $players;

    /**
     * Debug constructor.
     *
     * @param Connection $connection
     * @param PlayerStorage $playerStorage
     * @param BestCheckPointsWidgetFactory $widget
     */
    public function __construct(Connection $connection, PlayerStorage $playerStorage, BestCheckPointsWidgetFactory $widget, Group $players)
    {
        $this->connection = $connection;
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
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


    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        $this->widget->create($this->players);
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // TODO: Implement onApplicationStop() method.
    }

    public function onPlayerConnect(Player $player)
    {

    }

    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // TODO: Implement onPlayerDisconnect() method.
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerInfoChanged() method.
    }

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerAlliesChanged() method.
    }
}
