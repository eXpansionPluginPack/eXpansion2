<?php

namespace eXpansionExperimantal\Bundle\WidgetLiveRankings\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansionExperimantal\Bundle\WidgetLiveRankings\Plugins\Gui\LiveRankingsWidgetFactory;
use Maniaplanet\DedicatedServer\Structures\Map;


class LiveRankings implements StatusAwarePluginInterface, ListenerInterfaceMpLegacyMap
{
    /** @var Factory */
    protected $factory;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var LiveRankingsWidgetFactory
     */
    private $widget;
    /**
     * @var Group
     */
    private $players;

    /**
     * @var Group
     */
    private $allPlayers;


    /**
     * Debug constructor.
     *
     * @param Factory                   $factory
     * @param PlayerStorage             $playerStorage
     * @param LiveRankingsWidgetFactory $widget
     * @param Group                     $players
     * @param Group                     $allPlayers
     */
    public function __construct(
        Factory $factory,
        PlayerStorage $playerStorage,
        LiveRankingsWidgetFactory $widget,
        Group $players,
        Group $allPlayers
    ) {
        $this->factory = $factory;
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
        $this->allPlayers = $allPlayers;
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->widget->create($this->allPlayers);
            echo "displaying!\n";
        }
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map)
    {
        // TODO: Implement onBeginMap() method.
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

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // TODO: Implement onApplicationInit() method.
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        echo "rok\n";

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
}
