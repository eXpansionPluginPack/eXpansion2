<?php

namespace eXpansion\Framework\GameCurrencyBundle\Model;

use eXpansion\Framework\PlayersBundle\Model\Map\GamecurrencyTableMap;
use Propel\Runtime\Exception\PropelException;

/**
 * Class PlayerQueryBuilder
 *
 * @author    reaby
 * @copyright 2018 eXpansion
 * @package   eXpansion\Framework\GameCurrencyBundle\Model
 */
class GameCurrencyQueryBuilder
{
    /**
     * Find by login
     *
     * @param $billId
     * @return Gamecurrency
     */
    public function findByBillId($billId)
    {
        $playerQuery = GamecurrencyQuery::create();
        $gamecurrency = $playerQuery->findOneByBillid($billId);

        GamecurrencyTableMap::clearInstancePool();

        return $gamecurrency;
    }

    /**
     * @return Gamecurrency[]
     */
    public function findAll()
    {
        $playerQuery = GamecurrencyQuery::create();
        $result = $playerQuery->find()->getData();

        GamecurrencyTableMap::clearInstancePool();

        return $result;
    }

    /**
     * Save individual currency entry
     *
     * @param Gamecurrency $gamecurrency
     * @throws PropelException
     */
    public function save(Gamecurrency $gamecurrency)
    {
        // First clear references. entry has no references that needs saving.
        $gamecurrency->clearAllReferences(false);

        // Save and free memory.
        $gamecurrency->save();
        GamecurrencyTableMap::clearInstancePool();
    }
}
