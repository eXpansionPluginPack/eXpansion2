<?php

namespace eXpansion\Framework\PlayersBundle\Model;

use eXpansion\Framework\PlayersBundle\Model\Map\PlayerTableMap;
use Propel\Runtime\Propel;

/**
 * Class PlayerQueryBuilder
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\PlayersBundle\Model
 */
class PlayerQueryBuilder
{
    /**
     * Find by login
     *
     * @param $login
     *
     * @return Player
     */
    public function findByLogin($login)
    {
        $playerQuery = PlayerQuery::create();
        return $playerQuery->findOneByLogin($login);
    }

    /**
     * Save individual player.
     *
     * @param Player $player
     */
    public function save(Player $player)
    {
        $player->save();
    }

    /**
     * Save multiple player models at the tume
     *
     * @param Player[] $players
     */
    public function saveAll($players)
    {
        $connection = Propel::getWriteConnection(PlayerTableMap::DATABASE_NAME);
        $connection->beginTransaction();

        foreach ($players as $record){
            $this->save($record);
        }

        $connection->commit();
    }
}
