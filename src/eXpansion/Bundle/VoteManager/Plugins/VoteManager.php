<?php

namespace eXpansion\Bundle\VoteManager\Plugins;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\UpdateVoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\VoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyVote;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class VoteManager implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyVote, ListenerInterfaceExpTimer, ListenerInterfaceMpScriptMap
{

    const YES = "yes";
    const NO = "no";

    /**
     * @var Console
     */
    private $console;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var ChatNotification
     */
    private $chatNotification;

    /** @var Vote|null */
    public static $currentVote = null;

    /**
     * @var VoteWidgetFactory
     */
    private $voteWidgetFactory;
    /**
     * @var UpdateVoteWidgetFactory
     */
    private $updateVoteWidgetFactory;
    /**
     * @var Group
     */
    private $players;
    /**
     * @var JukeboxService
     */
    private $jukebox;
    /**
     * @var MapStorage
     */
    private $mapStorage;

    /** @var array */
    private $voteStarted = [];

    /**
     * VoteManager constructor.
     * @param Console $console
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param VoteWidgetFactory $voteWidgetFactory
     * @param UpdateVoteWidgetFactory $updateVoteWidgetFactory
     * @param Group $players
     * @param JukeboxService $jukebox
     * @param MapStorage $mapStorage
     */
    public function __construct(
        Console $console,
        Connection $connection,
        ChatNotification $chatNotification,
        VoteWidgetFactory $voteWidgetFactory,
        UpdateVoteWidgetFactory $updateVoteWidgetFactory,
        Group $players,
        JukeboxService $jukebox,
        MapStorage $mapStorage
    ) {
        $this->console = $console;
        $this->connection = $connection;
        $this->chatNotification = $chatNotification;
        $this->voteWidgetFactory = $voteWidgetFactory;
        $this->updateVoteWidgetFactory = $updateVoteWidgetFactory;
        $this->players = $players;
        $this->jukebox = $jukebox;

        $this->mapStorage = $mapStorage;
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
        // TODO: Implement onApplicationReady() method.
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
     * When a new vote is addressed
     *
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteNew(Player $player, $cmdName, $cmdValue)
    {
        switch ($cmdName) {
            case "NextMap":
                $this->connection->cancelVote();
                $this->startVote("NextMap");
                break;
            case "RestartMap":
                $this->connection->cancelVote();
                $this->startVote("RestartMap");
                break;
        }
    }

    /**
     * When vote gets cancelled
     *
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteCancelled(Player $player, $cmdName, $cmdValue)
    {
        if (!self::$currentVote) {
            return;
        } else {
            self::$currentVote = null;
            $this->voteWidgetFactory->destroy($this->players);
        }
    }

    /**
     * When vote Passes
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVotePassed(Player $player, $cmdName, $cmdValue)
    {

    }

    /**
     * When vote Fails
     * @param Player $player
     * @param string $cmdName
     * @param string $cmdValue
     *
     * @return void
     */
    public function onVoteFailed(Player $player, $cmdName, $cmdValue)
    {

    }

    public function onPreLoop()
    {
        // TODO: Implement onPreLoop() method.
    }

    public function onPostLoop()
    {
        // TODO: Implement onPostLoop() method.
    }

    public function updateVote($login, $type)
    {
        if (!self::$currentVote) {
            return;
        }

        switch ($type) {
            case self::YES:
                self::$currentVote->castYes($login);
                break;
            case self::NO:
                self::$currentVote->castNo($login);
                break;
        }
    }

    public function onEverySecond()
    {
        if (self::$currentVote) {
            if (self::$currentVote->updateVote(time())) {
                $this->processVote(self::$currentVote);
                self::$currentVote = null;
                $this->voteWidgetFactory->destroy($this->players);
            } else {
                $this->console->write(".");
                $this->updateVoteWidgetFactory->update($this->players);
            }
        }
    }

    /**
     * @return Vote
     */
    public function getCurrentVote()
    {
        return self::$currentVote;
    }

    public function startVote($type)
    {
        if (!array_key_exists($type, $this->voteStarted)) {
            $this->voteStarted[$type] = true;
            self::$currentVote = new Vote($type);
            $this->voteWidgetFactory->destroy($this->players);
            $this->updateVoteWidgetFactory->destroy($this->players);

            $this->voteWidgetFactory->create($this->players);
            $this->updateVoteWidgetFactory->create($this->players);
        } else {
            $this->chatNotification->sendMessage("|error| Vote of this type has already started.");
        }
    }


    private function processVote(Vote $vote)
    {
        if ($vote->getType() == "RestartMap") {
            $this->jukebox->addMap($this->mapStorage->getCurrentMap(), null, true);

            return;
        }
        if ($vote->getType() == "NextMap") {
            $this->connection->nextMap(false);

            return;
        }
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
        $this->voteStarted = [];
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
        // TODO: Implement onStartMapEnd() method.
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
        // TODO: Implement onEndMapStart() method.
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
        // TODO: Implement onEndMapEnd() method.
    }
}
