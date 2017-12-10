<?php

namespace eXpansion\Bundle\LocalMapRatings\Services;

use eXpansion\Bundle\LocalMapRatings\Model\Map\MapratingTableMap;
use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalMapRatings\Model\MapratingQueryBuilder;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use eXpansion\Framework\PlayersBundle\Model\PlayerQueryBuilder;
use Maniaplanet\DedicatedServer\Structures\Map;
use Propel\Runtime\Propel;


class MapRatingsService implements ListenerInterfaceExpApplication, ListenerInterfaceMpScriptMap,
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
     * @var MapratingQueryBuilder
     */
    private $mapratingQueryBuilder;
    /**
     * @var PlayerQueryBuilder
     */
    private $playerQueryBuilder;

    /**
     * MapRatingService constructor.
     * @param MapStorage            $mapStorage
     * @param PlayerStorage         $playerStorage
     * @param MapratingQueryBuilder $mapratingQueryBuilder
     * @param PlayerQueryBuilder    $playerQueryBuilder
     */
    public function __construct(
        MapStorage $mapStorage,
        PlayerStorage $playerStorage,
        MapratingQueryBuilder $mapratingQueryBuilder,
        PlayerQueryBuilder $playerQueryBuilder

    ) {
        $this->mapStorage = $mapStorage;
        $this->playerStorage = $playerStorage;
        $this->mapratingQueryBuilder = $mapratingQueryBuilder;
        $this->playerQueryBuilder = $playerQueryBuilder;
    }

    /**
     * @param Map $map
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    public function load(Map $map)
    {
        $this->changedRatings = [];

        /** @var Maprating[] $ratings */
        $ratings = $this->mapratingQueryBuilder->getRatingsForMap($map);

        foreach ($ratings as $rating) {
            $this->ratingsPerPlayer[$rating->getPlayer()->getLogin()] = $rating;
        }

    }


    public function save(Map $map)
    {

        $con = Propel::getWriteConnection(MapratingTableMap::DATABASE_NAME);
        $con->beginTransaction();
        foreach ($this->changedRatings as $rating) {
            $rating->save();
        }
        $con->commit();

        MapratingTableMap::clearInstancePool();
        MapratingTableMap::clearRelatedInstancePool();
    }

    /**
     * @param string $login
     * @param int    $score
     */
    public function changeRating($login, $score)
    {
        $rating = $this->getRating($login);
        $rating->setScore($score);
        $this->changedRatings[$login] = $rating;
        $this->ratingsPerPlayer[$login] = $rating;
    }


    private function getRating($login)
    {
        if (array_key_exists($login, $this->changedRatings)) {
            $rating = $this->changedRatings[$login];
        } else {
            if (array_key_exists($login, $this->ratingsPerPlayer)) {
                $rating = $this->ratingsPerPlayer[$login];
            } else {
                $rating = new Maprating();
                $player = $this->playerQueryBuilder->findByLogin($login);
                $rating->setPlayerId($player->getId());
                $rating->setPlayer($player);
                $rating->setMapuid($this->mapStorage->getCurrentMap()->uId);
            }
        }

        return $rating;
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

    }

    /**
     * @return Maprating[]
     */
    public function getRatingsPerPlayer(): array
    {
        return $this->ratingsPerPlayer;
    }

    /**
     * @param Maprating[] $ratingsPerPlayer
     */
    public function setRatingsPerPlayer(array $ratingsPerPlayer)
    {
        $this->ratingsPerPlayer = $ratingsPerPlayer;
    }
}
