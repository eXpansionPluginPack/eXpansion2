<?php

namespace eXpansion\Framework\Config\Model;

use eXpansion\Framework\PlayersBundle\Model\Player;
use eXpansion\Framework\PlayersBundle\Storage\PlayerDb;


/**
 * Class TextConfig
 *
 * @package eXpansion\Framework\Config\Model;
 * @author oliverde8
 */
class PlayerListConfig extends TextListConfig
{
    /** @var PlayerDb */
    protected $playerDb;

    /**
     * Get list of configured players.
     *
     * @return Player[]
     */
    public function get()
    {
        $list = parent::get();
        $players = [];

        foreach ($list as $login) {
            $player = $this->playerDb->get($login);
            if (is_null($player)) {
                $player = new Player();
                $player->setLogin($login);
            }

            $players[] = $player;
        }

        return $players;
    }
}
