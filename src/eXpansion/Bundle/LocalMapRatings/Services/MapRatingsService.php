<?php

namespace eXpansion\Bundle\LocalMapRatings\Services;

use eXpansion\Bundle\LocalMapRatings\Model\Map\MapratingTableMap;
use eXpansion\Bundle\LocalMapRatings\Model\Maprating;
use eXpansion\Bundle\LocalMapRatings\Model\MapratingQueryBuilder;
use eXpansion\Bundle\LocalRecords\Plugins\ChatNotification;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use eXpansion\Framework\PlayersBundle\Model\PlayerQueryBuilder;
use Maniaplanet\DedicatedServer\Structures\Map;
use Propel\Runtime\Propel;


class MapRatingsService implements ListenerInterfaceExpApplication, ListenerInterfaceMpScriptMatch,
    ListenerInterfaceMpScriptMap, ListenerInterfaceMpLegacyChat
{

    /** @var Maprating[] */
    protected $changedRatings;

    /** @var Maprating[] */
    protected $ratingsPerPlayer = [];
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
     * @var ChatNotification
     */
    private $chatNotification;
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * MapRatingService constructor.
     * @param MapStorage            $mapStorage
     * @param PlayerStorage         $playerStorage
     * @param MapratingQueryBuilder $mapratingQueryBuilder
     * @param PlayerQueryBuilder    $playerQueryBuilder
     * @param ChatNotification      $chatNotification
     * @param Dispatcher            $dispatcher
     */
    public function __construct(
        MapStorage $mapStorage,
        PlayerStorage $playerStorage,
        MapratingQueryBuilder $mapratingQueryBuilder,
        PlayerQueryBuilder $playerQueryBuilder,
        ChatNotification $chatNotification,
        Dispatcher $dispatcher

    ) {
        $this->mapStorage = $mapStorage;
        $this->playerStorage = $playerStorage;
        $this->mapratingQueryBuilder = $mapratingQueryBuilder;
        $this->playerQueryBuilder = $playerQueryBuilder;
        $this->chatNotification = $chatNotification;
        $this->dispatcher = $dispatcher;
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


    public function save()
    {

        $con = Propel::getWriteConnection(MapratingTableMap::DATABASE_NAME);
        $con->beginTransaction();

        foreach ($this->changedRatings as $rating) {
            $rating->save();
        }
        $con->commit();
        $this->changedRatings = [];

        MapratingTableMap::clearInstancePool();
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

        $this->dispatcher->dispatch("expansion.mapratings.changed", [$this->ratingsPerPlayer]);
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
                $rating->setLogin($login);
                $rating->setMapuid($this->mapStorage->getCurrentMap()->uId);
            }
        }

        return $rating;
    }

    /**
     * Called when a player chats.
     *
     * @param Player $player
     * @param        $text
     *
     * @return void
     */
    public function onPlayerChat(Player $player, $text)
    {
        if ($player->getPlayerId() == 0) {
            return;
        }

        if ($player->getPlayerId() != 0 && substr($text, 0, 1) != "/") {
            if ($text === "++") {
                $this->changeRating($player->getLogin(), 1);

                return;
            }

            if ($text === "--") {
                $this->changeRating($player->getLogin(), -1);

                return;
            }
        }
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
     * @return Maprating[]
     */
    public function getRatingsPerPlayer(): array
    {
        return $this->ratingsPerPlayer;
    }

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchStart($count, $time)
    {

    }

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchEnd($count, $time)
    {
        $this->load($this->mapStorage->getCurrentMap());
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchStart($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchEnd($count, $time)
    {
        $this->save();
    }

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnStart($count, $time)
    {
        // TODO: Implement onStartTurnStart() method.
    }

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnEnd($count, $time)
    {
        // TODO: Implement onStartTurnEnd() method.
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnStart($count, $time)
    {
        // TODO: Implement onEndTurnStart() method.
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnEnd($count, $time)
    {
        // TODO: Implement onEndTurnEnd() method.
    }

    /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundStart($count, $time)
    {
        // TODO: Implement onStartRoundStart() method.
    }

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundEnd($count, $time)
    {
        // TODO: Implement onStartRoundEnd() method.
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundStart($count, $time)
    {
        // TODO: Implement onEndRoundStart() method.
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundEnd($count, $time)
    {
        // TODO: Implement onEndRoundEnd() method.
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
        if ($restarted) {
            $this->save();
        }
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
        $this->save();
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
}
