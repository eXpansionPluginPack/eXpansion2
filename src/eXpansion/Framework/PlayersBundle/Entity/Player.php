<?php

namespace eXpansion\Framework\PlayersBundle\Entity;

/**
 * Player
 */
class Player
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $nicknameStripped;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $path;

    /**
     * @var int
     */
    private $wins = 0;

    /**
     * @return int
     */
    private $onlineTime = 0;

    /**
     * @var \DateTime
     */
    private $lastOnline;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Player
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     *
     * @return Player
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getNicknameStripped()
    {
        return $this->nicknameStripped;
    }

    /**
     * @param string $nicknameStripped
     *
     * @return Player
     */
    public function setNicknameStripped($nicknameStripped)
    {
        $this->nicknameStripped = $nicknameStripped;

        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Player
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getWins()
    {
        return $this->wins;
    }

    /**
     * @param int $wins
     *
     * @return Player
     */
    public function setWins($wins)
    {
        $this->wins = $wins;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOnlineTime()
    {
        return $this->onlineTime;
    }

    /**
     * @param mixed $onlineTime
     *
     * @return Player
     */
    public function setOnlineTime($onlineTime)
    {
        $this->onlineTime = $onlineTime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastOnline()
    {
        return $this->lastOnline;
    }

    /**
     * @param \DateTime $lastOnline
     *
     * @return Player
     */
    public function setLastOnline($lastOnline)
    {
        $this->lastOnline = $lastOnline;

        return $this;
    }
}

