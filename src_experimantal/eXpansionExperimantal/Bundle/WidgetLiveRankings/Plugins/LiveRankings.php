<?php

namespace eXpansionExperimantal\Bundle\WidgetLiveRankings\Plugins;

use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansionExperimantal\Bundle\WidgetLiveRankings\Plugins\Gui\LiveRankingsWidgetFactory;


class LiveRankings implements StatusAwarePluginInterface
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
        }
    }

}
