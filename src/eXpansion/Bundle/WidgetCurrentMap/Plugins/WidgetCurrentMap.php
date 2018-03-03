<?php

namespace eXpansion\Bundle\WidgetCurrentMap\Plugins;

use eXpansion\Bundle\LocalMapRatings\DataProviders\Listener\ListenerInterfaceExpMapRatings;
use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\WidgetCurrentMap\Plugins\Gui\CurrentMapWidgetFactory;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use Maniaplanet\DedicatedServer\Structures\Map;


class WidgetCurrentMap implements StatusAwarePluginInterface, ListenerInterfaceMpLegacyMap, ListenerInterfaceExpMapRatings
{

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
     * @param PlayerStorage           $playerStorage
     * @param CurrentMapWidgetFactory $widget
     * @param Group                   $players
     */
    public function __construct(
        PlayerStorage $playerStorage,
        CurrentMapWidgetFactory $widget,
        Group $players
    ) {
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
    }


    public function setStatus($status)
    {
        if ($status) {
            $this->widget->create($this->players);
        } else {
            $this->widget->destroy($this->players);
        }
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

    }

    /**
     * Called when map ratings are loaded.
     *
     * @param Maprating[] $ratings
     * @return void
     */
    public function onMapRatingsLoaded($ratings)
    {
        $this->widget->setMapRatings($ratings);
        $this->widget->update($this->players);
    }

    /**
     * Called when map ratings are changed.
     *
     * @param string      $login
     * @param int         $score
     * @param Maprating[] $ratings
     * @return void
     */
    public function onMapRatingsChanged($login, $score, $ratings)
    {
        $this->widget->setMapRatings($ratings);
        $this->widget->update($this->players);
    }
}
