<?php

namespace eXpansion\Bundle\Maps\Structure;

use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Structures\Map;

class JukeboxMap
{
    /** @var Map */
    protected $map;

    /** @var  Player */
    protected $player;


    public function __construct($map, $player)
    {
        $this->map = $map;
        $this->player = $player;
    }

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return Map
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param Map $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }


}
