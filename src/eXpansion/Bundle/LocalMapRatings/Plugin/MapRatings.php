<?php

namespace eXpansion\Bundle\LocalMapRatings\Plugin;


use eXpansion\Bundle\LocalMapRatings\DataProviders\Listener\MapRatingsDataListener;
use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalMapRatings\Plugin\Gui\MapRatingsWidget;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;

class MapRatings implements ListenerInterfaceExpApplication, MapRatingsDataListener
{

    /**
     * @var MapRatingsWidget
     */
    private $mapRatingsWidget;
    /**
     * @var Group
     */
    private $players;

    /**
     * MapRatings constructor.
     * @param MapRatingsWidget $mapRatingsWidget
     * @param Group            $players
     */
    public function __construct(MapRatingsWidget $mapRatingsWidget, Group $players)
    {
        $this->mapRatingsWidget = $mapRatingsWidget;
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
        $this->mapRatingsWidget->create($this->players);
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
     * Called when map ratings are loaded.
     *
     * @param Maprating[] $ratings
     */
    public function onMapRatingsLoaded($ratings)
    {
        $this->mapRatingsWidget->update($this->players);
    }

    /**
     * Called when map ratings are changed.
     *
     * @param Maprating[] $ratings
     */
    public function onMapRatingsChanged($ratings)
    {
        $this->mapRatingsWidget->update($this->players);
    }
}
