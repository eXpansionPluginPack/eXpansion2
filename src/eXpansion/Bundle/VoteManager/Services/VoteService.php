<?php

namespace eXpansion\Bundle\VoteManager\Services;

use eXpansion\Bundle\VoteManager\Plugins\Votes\AbstractVotePlugin;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;

class VoteService
{
    /** @var Console */
    protected $console;

    /** @var Connection */
    protected $connection;

    /** @var ChatNotification */
    protected $chatNotification;

    /** @var Dispatcher */
    protected $dispatcher;

    /** @var AbstractVotePlugin[] */
    protected $votePlugins = [];

    /** @var array mapping between native MP votes and equivalent expansion votes. */
    protected $voteMapping = [];

    /** @var AbstractVotePlugin */
    protected $currentVote = null;

    /**
     * VoteManager constructor.
     * @param Console $console
     * @param Connection $connection
     * @param ChatNotification $chatNotification
     * @param Dispatcher $dispatcher
     * @param AbstractVotePlugin[] $voteFactories
     */
    public function __construct(
        Console $console,
        Connection $connection,
        ChatNotification $chatNotification,
        Dispatcher $dispatcher,
        $voteFactories
    ) {
        $this->console = $console;
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
        $this->chatNotification = $chatNotification;

        foreach ($voteFactories as $voteFactory) {
            $this->votePlugins[$voteFactory->getCode()] = $voteFactory;

            foreach ($voteFactory->getReplacementTypes() as $replaces) {
                $this->voteMapping[$replaces] = $voteFactory->getCode();
            }
        }
    }

    /**
     * Reset ongoing vote.
     */
    public function reset()
    {
        if ($this->currentVote) {
            $this->currentVote->reset();
        }
        $this->currentVote = null;
    }

    /**
     * Cast vote
     *
     * @param string $login
     * @param string $type
     */
    public function castVote($login, $type)
    {
        if ($this->currentVote instanceof AbstractVotePlugin) {
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

    /**
     * Update the status of the vote.
     */
    public function update()
    {
        if ($this->currentVote) {
            $vote = $this->currentVote->getCurrentVote();
            $this->currentVote->update(time());

            switch ($vote->getStatus()) {
                case Vote::STATUS_CANCEL:
                    $this->dispatcher->dispatch("votemanager.votecancelled",
                        [$vote->getPlayer(), $vote->getType(), $vote]);
                    $this->currentVote = null;
                    $this->reset();
                    break;
                case Vote::STATUS_FAILED:
                    $this->dispatcher->dispatch("votemanager.votefailed",
                        [$vote->getPlayer(), $vote->getType(), $vote]);
                    $this->currentVote = null;
                    $this->reset();
                    break;
                case Vote::STATUS_PASSED:
                    $this->dispatcher->dispatch("votemanager.votepassed",
                        [$vote->getPlayer(), $vote->getType(), $vote]);
                    $this->currentVote = null;
                    $this->reset();
                    break;
            }
        }
    }

    /**
     * Cancel ongoing vote.
     */
    public function cancel()
    {
        if (!$this->currentVote) {
            return;
        }

        $this->currentVote->cancel();
        $this->update();
    }

    /**
     * @return AbstractVotePlugin
     */
    public function getCurrentVote()
    {
        return $this->currentVote;
    }

    /**
     * Start a vote.
     *
     * @param Player $player
     * @param string $typeCode
     * @param array  $params
     */
    public function startVote(Player $player, $typeCode, $params)
    {
        if ($this->getCurrentVote() !== null) {
            $this->chatNotification->sendMessage("expansion_votemanager.error.in_progress");
            return;
        }

        if (isset($this->voteMapping[$typeCode])) {
            $typeCode = $this->voteMapping[$typeCode];
        }

        if (!isset($this->votePlugins[$typeCode])) {
            $this->chatNotification->sendMessage("|error| Unknown vote type : $typeCode");
            return;
        }

        $this->currentVote = $this->votePlugins[$typeCode];
        $this->currentVote->start($player, $params);
        $this->connection->cancelVote();

        $this->dispatcher->dispatch(
            "votemanager.votenew",
            [$player, $this->currentVote->getCode(), $this->currentVote->getCurrentVote()]
        );
    }
}
