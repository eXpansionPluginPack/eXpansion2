<?php

namespace eXpansion\Bundle\MxKarma\Entity;

class MxRating
{
    /** @var int */
    protected $voteCount;

    /** @var int */
    protected $voteAverage;

    /** @var int */
    protected $modeVoteCount;

    /** @var int */
    protected $modeVoteAverage;

    /** @var string[login, number] */
    protected $votes = array();

    public function append($object)
    {
        if (!is_object($object)) {
            throw new \Exception("MXVote constructor got non object", 1, null);
        }
        $this->voteCount = $object->votecount;
        $this->voteAverage = $object->voteaverage;
        if ($object->modevotecount != -1) {
            $this->modeVoteCount = $object->modevotecount;
        }
        if ($object->modevoteaverage != -1) {
            $this->modeVoteAverage = $object->modevoteaverage;
        }
        if (is_array($object->votes)) {
            foreach ($object->votes as $vote) {
                $this->votes[] = $vote;
            }
        }
    }

    /**
     * @return string
     */
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @param string $votes
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    }

    /**
     * @return int
     */
    public function getModeVoteAverage()
    {
        return $this->modeVoteAverage;
    }

    /**
     * @param int $modeVoteAverage
     */
    public function setModeVoteAverage($modeVoteAverage)
    {
        $this->modeVoteAverage = $modeVoteAverage;
    }

    /**
     * @return int
     */
    public function getModeVoteCount()
    {
        return $this->modeVoteCount;
    }

    /**
     * @param int $modeVoteCount
     */
    public function setModeVoteCount($modeVoteCount)
    {
        $this->modeVoteCount = $modeVoteCount;
    }

    /**
     * @return int
     */
    public function getVoteAverage()
    {
        return $this->voteAverage;
    }

    /**
     * @param int $voteAverage
     */
    public function setVoteAverage($voteAverage)
    {
        $this->voteAverage = $voteAverage;
    }

    /**
     * @return int
     */
    public function getVoteCount()
    {
        return $this->voteCount;
    }

    /**
     * @param int $voteCount
     */
    public function setVoteCount($voteCount)
    {
        $this->voteCount = $voteCount;
    }
}
