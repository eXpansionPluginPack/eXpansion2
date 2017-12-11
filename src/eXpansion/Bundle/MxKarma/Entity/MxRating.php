<?php

namespace eXpansion\Bundle\MxKarma\Entity;

use ArrayObject;

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

    /** @var mxVote[] */
    protected $votes = array();

    public function append($object)
    {
        if (!is_object($object)) {
            throw new \Exception("MXVote constructor got non object");
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
                $this->votes[$vote->login] = new MxVote($vote);
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
     * @param MxVote[] $votes
     * @return $this
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;

        return $this;
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
     * @return $this
     */
    public function setModeVoteAverage($modeVoteAverage)
    {
        $this->modeVoteAverage = $modeVoteAverage;

        return $this;
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
     * @return $this
     */
    public function setModeVoteCount($modeVoteCount)
    {
        $this->modeVoteCount = $modeVoteCount;

        return $this;
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
     * @return $this
     */
    public function setVoteAverage($voteAverage)
    {
        $this->voteAverage = $voteAverage;

        return $this;
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
     * @return $this
     */
    public function setVoteCount($voteCount)
    {
        $this->voteCount = $voteCount;

        return $this;
    }
}
