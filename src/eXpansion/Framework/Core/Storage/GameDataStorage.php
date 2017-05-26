<?php

namespace eXpansion\Framework\Core\Storage;
use Maniaplanet\DedicatedServer\Structures\GameInfos;

/**
 * Class GameDataStorage
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Storage
 */
class GameDataStorage
{
    /** @var  GameInfos */
    protected $gameInfos;

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
}