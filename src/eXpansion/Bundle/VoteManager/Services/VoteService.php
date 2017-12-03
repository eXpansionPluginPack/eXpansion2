<?php

namespace eXpansion\Bundle\VoteManager\Services;

use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyVote;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class VoteService implements ListenerInterfaceMpLegacyVote, ListenerInterfaceExpTimer, ListenerInterfaceMpScriptMap
{
    /**
     * @var Connection
     */
    public $connection;

    /** @var Console */
    public $console;

    public $removeVote = false;

    /** @var null|Vote */
    private $currentVote = null;

    /** @var array */
    private $votesStarted = [];

    /**
     * @var Dispatcher
     */
    private $dispatcher;
    /**
     * @var ChatNotification
     */
    private $chatNotification;

    /**
     * VoteManager constructor.
     * @param Console $console
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param Dispatcher $dispatcher
     */
    public function __construct(
        Console $console,
        Connection $connection,
        ChatNotification $chatNotification,
        Dispatcher $dispatcher
    ) {
        $this->console = $console;
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
        $this->chatNotification = $chatNotification;
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
                // disable default vote
                $this->connection->cancelVote();
                $this->startVote($player, "Exp_NextMap");
                break;
            case "RestartMap":
                // disable default vote
                $this->connection->cancelVote();
                $this->startVote($player, "Exp_RestartMap");
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
        if ($cmdValue instanceof Vote) {
            $this->currentVote = null;
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
        if ($cmdValue instanceof Vote) {
            $this->currentVote = null;
        }
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
        if ($cmdValue instanceof Vote) {
            $this->currentVote = null;
        }
    }

    public function onPreLoop()
    {
        // do nothing
    }

    public function onPostLoop()
    {
        //do nothing
    }

    public function updateVote($login, $type)
    {
        if ($this->currentVote instanceof Vote) {
            switch ($type) {
                case Vote::VOTE_YES:
                    $this->currentVote->castYes($login);
                    break;
                case Vote::VOTE_NO:
                    $this->currentVote->castNo($login);
                    break;
            }
        }
    }

    public function onEverySecond()
    {
        $vote = $this->currentVote;

        if ($vote !== null) {
            $vote->updateVote(time());

            switch ($vote->getStatus()) {
                case Vote::STATUS_CANCEL:
                    $this->dispatcher->dispatch("votemanager.votecancelled",
                        [$vote->getPlayer(), $vote->getType(), $this->currentVote]);
                    break;
                case Vote::STATUS_FAILED:
                    $this->dispatcher->dispatch("votemanager.votefailed",
                        [$vote->getPlayer(), $vote->getType(), $this->currentVote]);
                    break;
                case Vote::STATUS_PASSED:
                    $this->dispatcher->dispatch("votemanager.votepassed",
                        [$vote->getPlayer(), $vote->getType(), $this->currentVote]);
                    break;
            }
        }
    }

    /**
     * @return Vote
     */
    public function getCurrentVote()
    {
        return $this->currentVote;
    }

    public function startVote(Player $player, $type)
    {
        if ($this->getCurrentVote() !== null) {
            $this->chatNotification->sendMessage("|error| Vote already progressing.");

            return;
        }

        if (array_key_exists($type, $this->votesStarted) == false) {
            $this->votesStarted[$type] = "yep";
            $this->currentVote = new Vote($player, $type);
            $this->dispatcher->dispatch("votemanager.votenew", [$player, $type, $this->currentVote]);
        } else {
            /** @todo change this to toast when the service is ready */
            $this->chatNotification->sendMessage("|error| Vote of this type has already started.");
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
        $this->votesStarted = [];
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
