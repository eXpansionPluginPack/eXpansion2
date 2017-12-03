<?php

namespace eXpansion\Bundle\VoteManager\Structures;

use eXpansion\Framework\Core\Storage\Data\Player;

class Vote
{
    const VOTE_YES = "yes";
    const VOTE_NO = "no";

    const STATUS_FAILED = -1;
    const STATUS_CANCEL = 0;
    const STATUS_RUNNING = 1;
    const STATUS_PASSED = 2;

    protected $status = 1;

    protected $elapsedTime = 0;
    protected $totalTime = 30;

    /** @var int */
    protected $startTime = 0;

    protected $votes = [];

    /** @var string */
    private $type;

    protected $ratio = 0.57;
    /**
     * @var Player
     */
    private $player;

    public function __construct(Player $player, $type)
    {
        $this->startTime = time();
        $this->type = $type;
        $this->totalTime = 30;
        $this->player = $player;
    }


    public function castYes($login)
    {
        $this->votes[$login] = self::VOTE_YES;
    }

    public function castNo($login)
    {
        $this->votes[$login] = self::VOTE_NO;
    }

    /**
     *
     */
    public function getYes()
    {
        $value = 0;
        foreach ($this->votes as $login => $vote) {
            if ($vote === self::VOTE_YES) {
                $value++;
            }
        }

        return $value;
    }

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
     *
     */
    public function getNo()
    {
        $value = 0;
        foreach ($this->votes as $login => $vote) {
            if ($vote === self::VOTE_NO) {
                $value++;
            }
        }

        return $value;
    }


    /**
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * @return float
     */
    public function getTotalTime(): int
    {
        return $this->totalTime;
    }

    /**
     * @return int
     */
    public function getElapsedTime(): int
    {
        return $this->elapsedTime;
    }

    /**
     * @return float
     */
    public function getRatio(): float
    {
        return $this->ratio;
    }

    /**
     * @param float $ratio
     */
    public function setRatio(float $ratio)
    {
        $this->ratio = $ratio;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

}
