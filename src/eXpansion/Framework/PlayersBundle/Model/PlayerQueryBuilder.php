<?php

namespace eXpansion\Framework\PlayersBundle\Model;

use eXpansion\Framework\PlayersBundle\Model\Map\PlayerTableMap;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Propel;

/**
 * Class PlayerQueryBuilder
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package   eXpansion\Framework\PlayersBundle\Model
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
        $player = $playerQuery->findOneByLogin($login);

        PlayerTableMap::clearInstancePool();
        return $player;
    }

    public function findAll()
    {
        $playerQuery = PlayerQuery::create();
        $result = $playerQuery->find()->getData();

        PlayerTableMap::clearInstancePool();
        return $result;
    }

    /**
     * @return Player[]
     */
    public function findDummy()
    {
        $playerQuery = PlayerQuery::create();

        return $playerQuery->filterByLogin("%dummyplayer_%", PlayerQuery::LIKE)->find()->getData();
    }


    /**
     * Save individual player.
     *
     * @param Player $player
     *
     * @throws PropelException
     */
    public function save(Player $player)
    {
        // First clear references. Player has no references that needs saving.
        $player->clearAllReferences(false);

        // Save and free memory.
        $player->save();
        PlayerTableMap::clearInstancePool();
    }

    /**
     * Save multiple player models at the tume
     *
     * @param Player[] $players
     *
     * @throws PropelException
     */
    public function saveAll($players)
    {
        $connection = Propel::getWriteConnection(PlayerTableMap::DATABASE_NAME);
        $connection->beginTransaction();

        foreach ($players as $record) {
            $this->save($record);
        }

        $connection->commit();
        PlayerTableMap::clearInstancePool();
    }
}
