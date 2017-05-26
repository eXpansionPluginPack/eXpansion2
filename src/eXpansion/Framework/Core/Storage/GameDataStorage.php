<?php

namespace eXpansion\Framework\Core\Storage;
use Maniaplanet\DedicatedServer\Structures\GameInfos;
use Maniaplanet\DedicatedServer\Structures\Version;
use oliverde8\AssociativeArraySimplified\AssociativeArray;

/**
 * Class GameDataStorage
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Storage
 */
class GameDataStorage
{
    /**  */
    const GAME_MODE_CODE_UNKNOWN = 'unknown';

    /** @var  GameInfos */
    protected $gameInfos;

    /** @var Version */
    protected $version;

    /**
     * @var AssociativeArray
     */
    protected $gameModeCodes;

    /**
     * GameDataStorage constructor.
     *
     * @param array $gameModeCodes
     */
    public function __construct(array $gameModeCodes)
    {
        $this->gameModeCodes = new AssociativeArray($gameModeCodes);
    }


    /**
     * @return GameInfos
     */
    public function getGameInfos()
    {
        return $this->gameInfos;
    }

    /**
     * @param GameInfos $gameInfos
     */
    public function setGameInfos($gameInfos)
    {
        $this->gameInfos = $gameInfos;
    }

    /**
     * @return Version
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param Version $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Get code of the game mode.
     *
     * @return mixed
     */
    public function getGameModeCode()
    {
        return $this->gameModeCodes->get($this->getGameInfos()->gameMode, self::GAME_MODE_CODE_UNKNOWN);
    }
}