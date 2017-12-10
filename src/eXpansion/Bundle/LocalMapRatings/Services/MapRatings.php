<?php

namespace eXpansion\Bundle\LocalMapRatings\Services;

use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalMapRatings\Model\MapratingQueryBuilder;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use Maniaplanet\DedicatedServer\Structures\Map;

class MapRatingService implements ListenerInterfaceExpApplication, ListenerInterfaceMpScriptMap,
    ListenerInterfaceMpScriptPodium
{

    /** @var Maprating[] */
    protected $changedRatings;

    /** @var Maprating[] */
    protected $ratingsPerPlayer;
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * @var Maprating[]
     */
    protected $ratings = [];
    /**
     * @var MapratingQueryBuilder
     */
    private $mapratingQueryBuilder;

    /**
     * MapRatingService constructor.
     * @param MapStorage            $mapStorage
     * @param PlayerStorage         $playerStorage
     * @param MapratingQueryBuilder $mapratingQueryBuilder
     */
    public function __construct(
        MapStorage $mapStorage,
        PlayerStorage $playerStorage,
        MapratingQueryBuilder $mapratingQueryBuilder

    ) {
        $this->mapStorage = $mapStorage;
        $this->playerStorage = $playerStorage;
        $this->mapratingQueryBuilder = $mapratingQueryBuilder;
    }

    /**
     * @param Map $map
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function load(Map $map)
    {
        /** @var Maprating[] $ratings */
        $ratings = $this->mapratingQueryBuilder->getRatingsForMap($map);

        foreach ($ratings as $rating) {
            $this->ratingsPerPlayer[$rating->getPlayer()->getLogin()] = $rating;
        }

    }


    public function save(Map $map)
    {
        /** @var Maprating[] $ratings */
        $ratings = $this->mapratingQueryBuilder->getRatingsForMap($map);

        foreach ($ratings as $rating) {
            $this->ratingsPerPlayer[$rating->getPlayer()->getLogin()] = $rating;
        }

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
        $this->changedRatings = [];
        $this->load($this->mapStorage->getCurrentMap());
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

    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        $this->changedRatings = [];
        $this->load($map);
    }

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onStartMapEnd() method.
    }

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onEndMapStart($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onEndMapStart() method.
    }

    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onEndMapEnd() method.
    }

    /**
     * Callback sent when the "onPodiumStart" section start.
     *
     * @param int $time Server time when the callback was sent
     * @return void
     */
    public function onPodiumStart($time)
    {
        $this->save();
    }

    /**
     * Callback sent when the "onPodiumEnd" section end.
     *
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onPodiumEnd($time)
    {
        // TODO: Implement onPodiumEnd() method.
    }
}
