<?php

namespace eXpansion\Bundle\VoteManager\Plugins\Votes;

use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;

/**
 * Class AbstractVotePlugin
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\VoteManager\Plugins\Votes
 */
abstract class AbstractVotePlugin
{
    /** @var PlayerStorage */
    protected $playerStorage;

    /** @var int */
    protected $duration;

    /** @var float */
    protected $ratio;

    /** @var Vote|null */
    protected $currentVote = null;

    /**
     * AbstractVotePlugin constructor.
     *
     * @param PlayerStorage $playerStorage
     * @param int $duration
     * @param float $ratio
     */
    public function __construct(PlayerStorage $playerStorage, int $duration, float $ratio)
    {
        $this->playerStorage = $playerStorage;
        $this->duration = $duration;
        $this->ratio = $ratio;
    }

    /**
     * Start a new vote.
     *
     * @param Player $player
     *
     * @return Vote|null
     */
    public function start(Player $player, $params)
    {
        $this->currentVote = new Vote($player, $this->duration, $this->ratio, $params);
        return $this->currentVote;
    }

    /**
     * Reset current vote session.
     */
    public function reset()
    {
        $this->currentVote = null;
    }

    /**
     * User votes yes
     *
     * @param string $login
     */
    public function castYes($login)
    {
        if ($this->currentVote) {
            $this->currentVote->castYes($login);
        }
    }

    /**
     * User votes no
     *
     * @param string $login
     */
    public function castNo($login)
    {
        if ($this->currentVote) {
            $this->currentVote->castYes($login);
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

        $this->currentVote->setStatus(Vote::STATUS_CANCEL);
    }

    /**
     * Update the status of the vote, and execute actions if vote passed.
     *
     * @param null $time
     */
    public function update($time = null)
    {
        if (!$this->currentVote || $this->currentVote->getStatus() == Vote::STATUS_CANCEL) {
            return;
        }

        if (is_null($time)) {
            $time = time();
        }

        // Check if vote passes when we suppose that all palyers that didn't vote would vote NO.
        if (($this->currentVote->getYes() / count($this->playerStorage->getOnline())) > $this->ratio) {
            $this->votePassed();
            return;
        }

        // If the vote is still not decided wait for the end to decide.
        if ($time - $this->currentVote->getStartTime() > $this->duration) {
            $totalVotes = $this->currentVote->getYes() + $this->currentVote->getNo();

            if ($totalVotes > 1 && $this->currentVote->getYes()/$totalVotes > $this->ratio) {
                $this->votePassed();
            } else {
                $this->voteFailed();
            }
        }
    }

    /**
     * @return Vote|null
     */
    public function getCurrentVote(): Vote
    {
        return $this->currentVote;
    }

    /**
     * Called when a vote passed.
     */
    protected function votePassed()
    {
        $this->currentVote->setStatus(Vote::STATUS_PASSED);
        $this->executeVotePassed();
        $this->reset();
    }

    /**
     * Called when a vote failed.
     */
    protected function voteFailed()
    {
        $this->currentVote->setStatus(Vote::STATUS_FAILED);
        $this->executeVoteFailed();
        $this->reset();
    }

    /**
     * Get question text to display for this vote.
     *
     * @return string
     */
    abstract public function getQuestion(): string;

    /**
     * Get type code of this vote.
     *
     * @return string
     */
    abstract public function getCode(): string;

    /**
     * Get native votes this votes replaces.
     *
     * @return string[]
     */
    abstract public function getReplacementTypes(): array;

    /**
     * Called when vote is passed.
     *
     * @return void
     */
    abstract protected function executeVotePassed();

    /**
     * Called when vote is failed.
     */
    abstract protected function executeVoteFailed();
}
