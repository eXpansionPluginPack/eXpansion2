<?php

namespace eXpansion\Bundle\LocalMapRatings\Services;

use eXpansion\Bundle\LocalMapRatings\Model\Map\MapratingTableMap;
use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalMapRatings\Model\MapratingQueryBuilder;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\PlayersBundle\Model\PlayerQueryBuilder;
use Maniaplanet\DedicatedServer\Structures\Map;
use Propel\Runtime\Propel;

class MapRatingsService
{
    protected $dispatcher;

    /** @var Maprating[] */
    protected $changedRatings;

    /** @var Maprating[] */
    protected $ratingsPerPlayer = [];
    /**
     * @var MapratingQueryBuilder
     */
    private $mapratingQueryBuilder;
    /**
     * @var PlayerQueryBuilder
     */
    private $playerQueryBuilder;
    /**
     * @var MapStorage
     */
    private $mapStorage;

    /**
     * MapRatingsService constructor.
     * @param MapratingQueryBuilder $mapratingQueryBuilder
     * @param PlayerQueryBuilder    $playerQueryBuilder
     * @param Dispatcher            $dispatcher
     * @param MapStorage            $mapStorage
     */
    public function __construct(
        MapratingQueryBuilder $mapratingQueryBuilder,
        PlayerQueryBuilder $playerQueryBuilder,
        Dispatcher $dispatcher,
        MapStorage $mapStorage
    ) {

        $this->mapratingQueryBuilder = $mapratingQueryBuilder;
        $this->playerQueryBuilder = $playerQueryBuilder;
        $this->dispatcher = $dispatcher;
        $this->mapStorage = $mapStorage;
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
        $this->ratingsPerPlayer = [];
        MapratingTableMap::clearInstancePool();

        /** @var Maprating[] $ratings */
        $ratings = $this->mapratingQueryBuilder->getRatingsForMap($map);

        foreach ($ratings as $rating) {
            $this->ratingsPerPlayer[$rating->getLogin()] = $rating;
        }

        $this->dispatcher->dispatch("expansion.mapratings.loaded", [$this->ratingsPerPlayer]);
    }

    /**
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function save()
    {
        if (count($this->changedRatings) > 0) {
            $con = Propel::getWriteConnection(MapratingTableMap::DATABASE_NAME);
            $con->beginTransaction();

            foreach ($this->changedRatings as $rating) {
                $rating->save();
            }
            $con->commit();
            $this->changedRatings = [];

            MapratingTableMap::clearInstancePool();
        }
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

        $param = [
            "login" => $login,
            "score" => $score,
            "ratings" => $this->ratingsPerPlayer,
        ];
        $this->dispatcher->dispatch("expansion.mapratings.changed", $param);
    }

    /**
     * @param $login
     * @return Maprating
     */
    public function getRating($login)
    {
        if (array_key_exists($login, $this->changedRatings)) {
            $rating = $this->changedRatings[$login];
        } else {
            if (array_key_exists($login, $this->ratingsPerPlayer)) {
                $rating = $this->ratingsPerPlayer[$login];
            } else {
                $rating = new Maprating();
                $rating->setLogin($login);
                $rating->setMapuid($this->mapStorage->getCurrentMap()->uId);
            }
        }

        return $rating;
    }

    /**
     * @return Maprating[]
     */
    public function getRatingsPerPlayer(): array
    {
        return $this->ratingsPerPlayer;
    }

}
