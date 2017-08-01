<?php

namespace eXpansion\Bundle\LocalRecords\Entity;

/**
 * Class Record
 *
 * @package eXpansion\Bundle\LocalRecords;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Record
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $mapUid;

    /** @var int */
    protected $playerLogin;

    /** @var int */
    protected $nbLaps;

    /** @var int */
    protected $score;

    /** @var int */
    protected $nbFinish;

    /** @var int */
    protected $avgScore;

    /** @var string */
    protected $checkpoints;

    /** @var  \DateTime */
    protected $date;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMapUid()
    {
        return $this->mapUid;
    }

    /**
     * @param string $mapUid
     */
    public function setMapUid($mapUid)
    {
        $this->mapUid = $mapUid;
    }

    /**
     * @return int
     */
    public function getPlayerLogin()
    {
        return $this->playerLogin;
    }

    /**
     * @param int $playerLogin
     */
    public function setPlayerLogin($playerLogin)
    {
        $this->playerLogin = $playerLogin;
    }

    /**
     * @return int
     */
    public function getNbLaps()
    {
        return $this->nbLaps;
    }

    /**
     * @param int $nbLaps
     */
    public function setNbLaps($nbLaps)
    {
        $this->nbLaps = $nbLaps;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return int
     */
    public function getNbFinish()
    {
        return $this->nbFinish;
    }

    /**
     * @param int $nbFinish
     */
    public function setNbFinish($nbFinish)
    {
        $this->nbFinish = $nbFinish;
    }

    /**
     * @return int
     */
    public function getAvgScore()
    {
        return $this->avgScore;
    }

    /**
     * @param int $avgScore
     */
    public function setAvgScore($avgScore)
    {
        $this->avgScore = $avgScore;
    }

    /**
     * @return string
     */
    public function getCheckpoints()
    {
        return $this->checkpoints;
    }

    /**
     * @param string $checkpoints
     */
    public function setCheckpoints($checkpoints)
    {
        $this->checkpoints = $checkpoints;
    }

    /**
     * Get checkpoint times
     *
     * @return int[]
     */
    public function getCheckpointTimes()
    {
        return explode(',', $this->getCheckpoints());
    }

    /**
     * Set checkpoint times.
     *
     * @param int[]
     */
    public function setCheckpointTimes($checkpointTimes)
    {
            $this->setCheckpoints(implode(',', $checkpointTimes));
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}