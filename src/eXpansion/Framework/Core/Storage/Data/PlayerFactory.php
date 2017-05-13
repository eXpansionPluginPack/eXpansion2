<?php

namespace eXpansion\Framework\Core\Storage\Data;
use Maniaplanet\DedicatedServer\Structures\PlayerDetailedInfo;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;

/**
 * Factory to create player data.
 *
 * @package eXpansion\Framework\Core\Storage\Data
 */
class PlayerFactory
{
    protected $class;

    /**
     * PlayerFactory constructor.
     *
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Create a player obhect.
     *
     * @param PlayerInfo $playerInfo
     * @param PlayerDetailedInfo $playerDetailedInfo
     *
     * @return Player
     */
    public function createPlayer(PlayerInfo $playerInfo, PlayerDetailedInfo $playerDetailedInfo)
    {
        $class = $this->class;
        $player = new $class();

        $player->merge($playerInfo);
        $player->merge($playerDetailedInfo);

        return $player;
    }
}
