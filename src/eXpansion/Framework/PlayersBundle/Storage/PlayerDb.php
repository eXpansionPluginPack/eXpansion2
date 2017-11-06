<?php

namespace eXpansion\Framework\PlayersBundle\Storage;

use \eXpansion\Framework\PlayersBundle\Plugins\Player as PlayerPlugin;

/**
 * Class Player
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\PlayersBundle\Storage
 */
class PlayerDb
{
    /** @var PlayerPlugin  */
    protected $playerPlugin;

    /**
     * Player constructor.
     *
     * @param PlayerPlugin $playerPlugin
     */
    public function __construct(PlayerPlugin $playerPlugin)
    {
        $this->playerPlugin = $playerPlugin;
    }

    /**
     * Get stored player. Only currently connected players are stored.
     *
     * @param $playerLogin
     *
     * @return mixed|null
     */
    public function get($playerLogin)
    {
        return $this->playerPlugin->getPlayer($playerLogin);
    }
}
