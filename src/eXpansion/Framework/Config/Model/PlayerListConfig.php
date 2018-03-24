<?php

namespace eXpansion\Framework\Config\Model;

use eXpansion\Framework\Config\Services\ConfigManagerInterface;
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

    public function __construct(
        $path,
        $scope,
        $name,
        $description,
        $defaultValue,
        ConfigManagerInterface $configManager,
        PlayerDb $playerDb
    )
    {
        parent::__construct($path, $scope, $name, $description, $defaultValue, $configManager);
        $this->playerDb = $playerDb;
    }


    /**
     * @inheritdoc
     */
    public function add($element)
    {
       if (is_object($element)) {
           return parent::add($element->getLogin());
       }

       return parent::add($element);
    }

    /**
     * @inheritdoc
     */
    public function remove($element)
    {
        if (is_object($element)) {
            return parent::remove($element->getLogin());
        }

        return parent::remove($element);    }


    /**
     * Get list of configured players.
     *
     * @return Player[]
     */
    public function get()
    {
        $players = [];

        foreach (parent::get() as $login) {
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
