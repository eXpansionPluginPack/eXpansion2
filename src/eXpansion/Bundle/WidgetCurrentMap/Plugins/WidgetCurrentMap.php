<?php

namespace eXpansion\Bundle\WidgetCurrentMap\Plugins;

use eXpansion\Bundle\WidgetCurrentMap\Plugins\Gui\CurrentMapWidgetFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;


class WidgetCurrentMap implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyMap
{
    /** @var Connection */
    protected $connection;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var CurrentMapWidgetFactory
     */
    private $widget;
    /**
     * @var Group
     */
    private $players;

    /**
     * Debug constructor.
     *
     * @param Connection              $connection
     * @param PlayerStorage           $playerStorage
     * @param CurrentMapWidgetFactory $widget
     * @param Group                   $players
     */
    public function __construct(
        Connection $connection,
        PlayerStorage $playerStorage,
        CurrentMapWidgetFactory $widget,
        Group $players
    ) {
        $this->connection = $connection;
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
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

    }

     /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map)
    {
        $this->widget->update($this->players);
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onEndMap(Map $map)
    {
        // TODO: Implement onEndMap() method.
    }
}
