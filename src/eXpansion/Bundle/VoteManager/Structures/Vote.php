<?php

namespace eXpansion\Bundle\VoteManager\Structures;

class Vote
{
    const YES = "yes";
    const NO = "no";

    protected $elapsedTime = 0;
    protected $totalTime = 30;

    /** @var int */
    protected $startTime = 0;

    protected $votes = [];

    /** @var string */
    private $type;

    protected $ratio = 0.57;

    public function __construct($type)
    {
        $this->startTime = time();
        $this->type = $type;
        $this->totalTime = 30;
    }


    public function castYes($login)
    {
        $this->votes[$login] = self::YES;
    }

    public function castNo($login)
    {
        $this->votes[$login] = self::NO;
    }

    /**
     *
     */
    public function getYes()
    {
        $value = 0;
        foreach ($this->votes as $login => $vote) {
            if ($vote === self::YES) {
                $value++;
            }
        }

        return $value;
    }

    function updateVote($time)
    {
        $this->elapsedTime = $time - $this->startTime;
        if ($this->elapsedTime > $this->totalTime) {
            return true;
        }

        $total = $this->getYes() + $this->getNo();
        if ($total > 0) {
            if (($this->getYes() / $total) > $this->ratio) {
                return true;
            }

            if (1 - ($this->getYes() / $total) > 0.9) {
                return true;
            }

        }

        return false;
    }


    /**
     *
     */
    public function getNo()
    {
        $value = 0;
        foreach ($this->votes as $login => $vote) {
            if ($vote === self::NO) {
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

}
