<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 12/03/2017
 * Time: 16:26
 */

namespace eXpansion\Framework\Core\Storage\Data;

use Maniaplanet\DedicatedServer\Structures\FileDesc;
use Maniaplanet\DedicatedServer\Structures\Skin;

/**
 * All data related to a player/spectator on the server.
 *
 * @package eXpansion\Framework\Core\Storage\Data
 */
class Player
{
    /** @var string */
    protected $login;

    /** @var bool */
    protected $isConnected = true;

    /** @var string */
    protected $nickName;

    /** @var int */
    protected $playerId;

    /** @var int */
    protected $teamId;

    /** @var bool */
    protected $isInOfficialMode;

    /** @var int */
    protected $ladderRanking;

    /** @var int */
    protected $spectatorStatus;

    /** @var int */
    protected $flags;

    /** @var int */
    protected $forceSpectator;

    /** @var bool */
    protected $isReferee;

    /** @var bool */
    protected $isPodiumReady;

    /** @var bool */
    protected $isUsingStereoscopy;

    /** @var bool */
    protected $isManagedByAnOtherServer;

    /** @var bool */
    protected $isServer;

    /** @var bool */
    protected $hasPlayerSlot;

    /** @var bool */
    protected $isBroadcasting;

    /** @var bool */
    protected $hasJoinedGame = false;

    /** @var bool */
    protected $spectator;

    /** @var bool */
    protected $temporarySpectator;

    /** @var bool */
    protected $pureSpectator;

    /** @var bool */
    protected $autoTarget;

    /** @var int */
    protected $currentTargetId;

    /** @var string */
    protected $path;

    /** @var string */
    protected $language;

    /** @var string */
    protected $clientVersion;

    /** @var string */
    protected $clientTitleVersion;

    /** @var string */
    protected $iPAddress;

    /** @var int */
    protected $downloadRate;

    /** @var int */
    protected $uploadRate;

    /** @var FileDesc */
    protected $avatar;

    /** @var Skin[] */
    protected $skins;

    /** @var mixed[] */
    protected $ladderStats;

    /** @var int */
    protected $hoursSinceZoneInscription;

    /** @var string */
    protected $broadcasterLogin;

    /** @var string[] */
    protected $allies = array();

    /** @var string */
    protected $clubLink;
    /** @var int */
    protected $rank;

    /** @var int */
    protected $bestTime;

    /** @var int[] */
    protected $bestCheckpoints;

    /** @var int */
    protected $score;

    /** @var int */
    protected $nbrLapsFinished;

    /** @var float */
    protected $ladderScore;

    /**
     * @return boolean
     */
    public function isIsConnected()
    {
        return $this->isConnected;
    }

    /**
     * @return string
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * @return int
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @return int
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * @return boolean
     */
    public function isIsSpectator()
    {
        return $this->spectatorStatus != 0;
    }

    /**
     * @return boolean
     */
    public function isIsInOfficialMode()
    {
        return $this->isInOfficialMode;
    }

    /**
     * @return int
     */
    public function getLadderRanking()
    {
        return $this->ladderRanking;
    }

    /**
     * @return int
     */
    public function getSpectatorStatus()
    {
        return $this->spectatorStatus;
    }

    /**
     * @return int
     */
    public function getFlags()
    {
        return $this->flags;
    }

    /**
     * @return int
     */
    public function getForceSpectator()
    {
        return $this->forceSpectator;
    }

    /**
     * @return boolean
     */
    public function isIsReferee()
    {
        return $this->isReferee;
    }

    /**
     * @return boolean
     */
    public function isIsPodiumReady()
    {
        return $this->isPodiumReady;
    }

    /**
     * @return boolean
     */
    public function isIsUsingStereoscopy()
    {
        return $this->isUsingStereoscopy;
    }

    /**
     * @return boolean
     */
    public function isIsManagedByAnOtherServer()
    {
        return $this->isManagedByAnOtherServer;
    }

    /**
     * @return boolean
     */
    public function isIsServer()
    {
        return $this->isServer;
    }

    /**
     * @return boolean
     */
    public function isHasPlayerSlot()
    {
        return $this->hasPlayerSlot;
    }

    /**
     * @return boolean
     */
    public function isIsBroadcasting()
    {
        return $this->isBroadcasting;
    }

    /**
     * @return boolean
     */
    public function isHasJoinedGame()
    {
        return $this->hasJoinedGame;
    }

    /**
     * @return boolean
     */
    public function isSpectator()
    {
        return $this->spectatorStatus != 0;
    }

    /**
     * @return boolean
     */
    public function isTemporarySpectator()
    {
        return $this->temporarySpectator;
    }

    /**
     * @return boolean
     */
    public function isPureSpectator()
    {
        return $this->pureSpectator;
    }

    /**
     * @return boolean
     */
    public function isAutoTarget()
    {
        return $this->autoTarget;
    }

    /**
     * @return int
     */
    public function getCurrentTargetId()
    {
        return $this->currentTargetId;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    public function getClientVersion()
    {
        return $this->clientVersion;
    }

    /**
     * @return string
     */
    public function getClientTitleVersion()
    {
        return $this->clientTitleVersion;
    }

    /**
     * @return string
     */
    public function getIPAddress()
    {
        return $this->iPAddress;
    }

    /**
     * @return int
     */
    public function getDownloadRate()
    {
        return $this->downloadRate;
    }

    /**
     * @return int
     */
    public function getUploadRate()
    {
        return $this->uploadRate;
    }

    /**
     * @return FileDesc
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @return Skin[]
     */
    public function getSkins()
    {
        return $this->skins;
    }

    /**
     * @return \mixed[]
     */
    public function getLadderStats()
    {
        return $this->ladderStats;
    }

    /**
     * @return int
     */
    public function getHoursSinceZoneInscription()
    {
        return $this->hoursSinceZoneInscription;
    }

    /**
     * @return string
     */
    public function getBroadcasterLogin()
    {
        return $this->broadcasterLogin;
    }

    /**
     * @return string[]
     */
    public function getAllies()
    {
        return $this->allies;
    }

    /**
     * @return string
     */
    public function getClubLink()
    {
        return $this->clubLink;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @return int
     */
    public function getBestTime()
    {
        return $this->bestTime;
    }

    /**
     * @return int[]
     */
    public function getBestCheckpoints()
    {
        return $this->bestCheckpoints;
    }

    /**
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getNbrLapsFinished()
    {
        return $this->nbrLapsFinished;
    }

    /**
     * @return float
     */
    public function getLadderScore()
    {
        return $this->ladderScore;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param \Maniaplanet\DedicatedServer\Structures\Player|array $data
     *
     * @return $this
     */
    function merge($data)
    {
        foreach ($data as $key => $value) {
            $key = lcfirst($key);
            $this->$key = $value;
        }

        return $this;
    }
}
