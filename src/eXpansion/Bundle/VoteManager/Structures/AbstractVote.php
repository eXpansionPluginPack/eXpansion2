<?php

namespace eXpansion\Bundle\VoteManager\Structures;

use eXpansion\Framework\Core\Storage\Data\Player;

abstract class AbstractVote
{
    const VOTE_YES = "yes";
    const VOTE_NO = "no";

    const STATUS_FAILED = -1;
    const STATUS_CANCEL = 0;
    const STATUS_RUNNING = 1;
    const STATUS_PASSED = 2;

    /** @var Player Player that started the vote */
    private $player;

    /** @var string */
    private $type;

    /** @var int Time the vote will take */
    protected $totalTime = 30;

    /** @var float Ration for the vote to pass. */
    protected $ratio = 0.57;

    /** @var int Current status of the vote. */
    protected $status = 1;

    /** @var int Time elapsed since vote started */
    protected $elapsedTime = 0;

    /** @var int */
    protected $startTime = 0;

    /** @var array */
    protected $votes = [];



    /**
     * Vote constructor.
     *
     * @param Player $player
     * @param string $type
     * @param int $duration
     * @param float $ration
     */
    public function __construct(Player $player, $type, $duration = 30, $ration = 0.57)
    {
        $this->startTime = time();
        $this->totalTime = $duration;
        $this->type = $type;
        $this->ratio = $ration;
        $this->player = $player;
    }

    /**
     * User votes yes
     *
     * @param string $login
     */
    public function castYes($login)
    {
        $this->votes[$login] = self::VOTE_YES;
    }

    /**
     * User votes no
     *
     * @param string $login
     */
    public function castNo($login)
    {
        $this->votes[$login] = self::VOTE_NO;
    }

    /**
     * Get number of yes votes.
     *
     * @return int
     */
    public function getYes()
    {
        return $this->countVote(self::VOTE_YES);
    }

    /**
     * Get number of no votes.
     *
     * @return int
     */
    public function getNo()
    {
        return $this->countVote(self::VOTE_NO);
    }

    /**
     * Count votes of a certain type.
     *
     * @param string $toCount
     *
     * @return int
     */
    protected function countVote($toCount)
    {
        $value = 0;
        foreach ($this->votes as $login => $vote) {
            if ($vote === $toCount) {
                $value++;
            }
        }

        return $value;
    }

    /**
     * Update status of the vite and change status if needed.
     *
     * @param $time
     */
    function updateVote($time)
    {
        $this->elapsedTime = $time - $this->startTime;
        if ($this->elapsedTime > $this->totalTime) {
            $this->status = self::STATUS_FAILED;
        }

        $total = $this->getYes() + $this->getNo();
        if ($total > 0) {
            if (($this->getYes() / $total) > $this->ratio) {
                $this->status = self::STATUS_PASSED;
            }

            if (1 - ($this->getYes() / $total) > 0.9) {
                $this->status = self::STATUS_FAILED;
            }

        }
    }


    /**
     * Get timestamp at which votes started.
     *
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * Get duration of the votes.
     *
     * @return float
     */
    public function getTotalTime(): int
    {
        return $this->totalTime;
    }

    /**
     * Get time elapsed since vote started.
     *
     * @return int
     */
    public function getElapsedTime(): int
    {
        return $this->elapsedTime;
    }

    /**
     * Get ration to pass vote.
     *
     * @return float
     */
    public function getRatio(): float
    {
        return $this->ratio;
    }

    /**
     * Get player that started the vote.
     *
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * Get current status of the vote.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public abstract function getQuestion() : string;

    public abstract function executeVotePassed();

    public abstract function executeVoteFailed();
}
