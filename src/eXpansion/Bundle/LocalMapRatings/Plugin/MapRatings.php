<?php

namespace eXpansion\Bundle\LocalMapRatings\Plugin;

use eXpansion\Bundle\LocalMapRatings\Services\MapRatingsService;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use Maniaplanet\DedicatedServer\Structures\Map;
use Propel\Runtime\Exception\PropelException;
use Psr\Log\LoggerInterface;


class MapRatings implements ListenerInterfaceExpApplication, ListenerInterfaceMpScriptMatch,
    ListenerInterfaceMpScriptMap, ListenerInterfaceMpLegacyChat
{
    /**
     * @var MapStorage
     */
    private $mapStorage;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;

    /**
     * @var Group
     */
    private $players;
    /**
     * @var MapRatingsService
     */
    private $mapRatingsService;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * MapRatingService constructor.
     * @param MapStorage $mapStorage
     * @param PlayerStorage $playerStorage
     * @param MapRatingsService $mapRatingsService
     * @param Group $players
     * @param LoggerInterface $logger
     */
    public function __construct(
        MapStorage $mapStorage,
        PlayerStorage $playerStorage,
        MapRatingsService $mapRatingsService,
        Group $players,
        LoggerInterface $logger
    )
    {
        $this->mapStorage = $mapStorage;
        $this->playerStorage = $playerStorage;
        $this->players = $players;
        $this->mapRatingsService = $mapRatingsService;
        $this->logger = $logger;
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
                $this->mapRatingsService->changeRating($player->getLogin(), 1);

                return;
            }

            if ($text === "--") {
                $this->mapRatingsService->changeRating($player->getLogin(), -1);

                return;
            }
        }
    }

    /**
     * helper function to load mapratings for current map
     * @param Map $map
     */
    private function loadRatings(Map $map)
    {
        try {
            $this->mapRatingsService->load($map);
        } catch (PropelException $e) {
            $this->logger->error("error loading map ratings", ["exception" => $e]);
        }
    }

    private function saveRatings()
    {
        try {
            $this->mapRatingsService->save();
        } catch (PropelException $e) {
            $this->logger->error("error saving map ratings", ["exception" => $e]);
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
        $this->loadRatings($this->mapStorage->getCurrentMap());
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
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
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
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
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
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchEnd($count, $time)
    {
        $this->saveRatings();
    }

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnStart($count, $time)
    {

    }

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnStart($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundStart($count, $time)
    {

    }

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundStart($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundEnd($count, $time)
    {

    }


    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return void
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        if ($restarted) {
            $this->saveRatings();
        }

        $this->loadRatings($map);
    }

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return void
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {

    }

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return void
     */
    public function onEndMapStart($count, $time, $restarted, Map $map)
    {
        $this->saveRatings();
    }

    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map $map Map started with.
     *
     * @return void
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map)
    {

    }

}
