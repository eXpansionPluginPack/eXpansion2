<?php

namespace eXpansion\Bundle\VoteManager\Services;

use eXpansion\Bundle\VoteManager\Services\VoteFactories\AbstractFactory;
use eXpansion\Bundle\VoteManager\Structures\AbstractVote;
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
    /** @var Console */
    protected $console;

    /** @var Connection */
    protected $connection;

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var Dispatcher */
    protected $dispatcher;

    /** @var AbstractFactory[] */
    protected $voteFactories = [];

    /** @var array mapping between native MP votes and equivalent expansion votes. */
    protected $voteMapping = [];

    /** @var null|AbstractVote */
    protected $currentVote = null;

    /** @var array */
    protected $votesStarted = [];



    /**
     * VoteManager constructor.
     * @param Console $console
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param Dispatcher $dispatcher
     * @param AbstractFactory[] $voteFactories
     */
    public function __construct(
        Console $console,
        Connection $connection,
        ChatNotification $chatNotification,
        Dispatcher $dispatcher,
        $voteFactories
    )
    {
        $this->console = $console;
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
        $this->chatNotification = $chatNotification;

        foreach ($voteFactories as $voteFactory) {
            $this->voteFactories[$voteFactory->getVoteCode()] = $voteFactory;

            foreach ($voteFactory->getReplacementTypes() as $replaces) {
                $this->voteMapping[$replaces] = $voteFactory->getVoteCode();
            }
        }
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
        if (!($cmdValue instanceof AbstractVote)) {
            if (isset($this->voteMapping[$cmdName])) {
                // disable default vote
                $this->connection->cancelVote();
                $this->startVote($player, $this->voteMapping[$cmdName]);
            }
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
        if ($cmdName == null && $cmdValue == null && $this->currentVote instanceof AbstractVote) {
            $this->currentVote->setStatus(Vote::STATUS_CANCEL);
        }

        if ($cmdValue instanceof AbstractVote) {
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
        if ($cmdValue instanceof AbstractVote) {
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
        if ($cmdValue instanceof AbstractVote) {
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
        if ($this->currentVote instanceof AbstractVote) {
            switch ($type) {
                case AbstractVote::VOTE_YES:
                    $this->currentVote->castYes($login);
                    break;
                case AbstractVote::VOTE_NO:
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
                case AbstractVote::STATUS_CANCEL:
                    $this->dispatcher->dispatch("votemanager.votecancelled",
                        [$vote->getPlayer(), $vote->getType(), $this->currentVote]);
                    break;
                case AbstractVote::STATUS_FAILED:
                    $this->dispatcher->dispatch("votemanager.votefailed",
                        [$vote->getPlayer(), $vote->getType(), $this->currentVote]);
                    break;
                case AbstractVote::STATUS_PASSED:
                    $this->dispatcher->dispatch("votemanager.votepassed",
                        [$vote->getPlayer(), $vote->getType(), $this->currentVote]);
                    break;
            }
        }
    }

    /**
     * @return AbstractVote
     */
    public function getCurrentVote()
    {
        return $this->currentVote;
    }


    public function startVote(Player $player, $type)
    {
        if ($this->getCurrentVote() !== null) {
            $this->chatNotification->sendMessage("expansion_votemanager.error.in_progress");
            return;
        }

        if (!isset($this->voteFactories[$type])) {
            $this->chatNotification->sendMessage("|error| Unknown vote type : $type");
        }

        if (array_key_exists($type, $this->votesStarted) == false) {
            $this->votesStarted[$type] = "yep";
            $this->currentVote = $this->voteFactories[$type]->create($player);
            $this->dispatcher->dispatch("votemanager.votenew", [$player, $type, $this->currentVote]);
        } else {
            $this->chatNotification->sendMessage("expansion_votemanager.error.already_started");
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
        // Nothing
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
        if ($this->currentVote instanceof Vote) {
            $this->currentVote->setStatus(Vote::STATUS_CANCEL);
        }
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
        // Nothing
    }
}
