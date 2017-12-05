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

    /** @var Player Player that started the vote */
    private $player;

    /** @var string */
    private $type;

    /** @var int */
    protected $startTime = 0;

    /** @var array */
    protected $params;

    /** @var array */
    protected $votes = [];

    /** @var int */
    protected $status = self::STATUS_RUNNING;

    /**
     * Vote constructor.
     *
     * @param Player $player
     * @param string $type
     * @param int $duration
     * @param float $ration
     */
    public function __construct(Player $player, $type, $duration = 30, $ration = 0.57, $params = [])
    {
        $this->startTime = time();
        $this->type = $type;
        $this->player = $player;
        $this->params = $params;
        $this->votes = [];
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
     * Get timestamp at which votes started.
     *
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->startTime;
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
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

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
