<?php

namespace eXpansion\Bundle\LocalRecords\Services;

use eXpansion\Framework\PlayersBundle\Storage\PlayerDb;


/**
 * Class RecordHandlerFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Services;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordHandlerFactory
{
    /** @var PlayerDb */
    protected $playerDb;

    /** @var string */
    protected $ordering;

    /** @var int */
    protected $nbRecords;

    /** @var string */
    protected $className;

    /**
     * RecordHandlerFactory constructor.
     *
     * @param PlayerDb         $playerDb
     * @param string           $ordering
     * @param int              $nbRecords
     * @param string           $className
     */
    public function __construct(
        PlayerDb $playerDb,
        $ordering,
        $nbRecords,
        $className = RecordHandler::class
    ) {
        $this->playerDb = $playerDb;
        $this->ordering = $ordering;
        $this->nbRecords = $nbRecords;
        $this->className = $className;
    }


    public function create()
    {
        $class = $this->className;

        return new $class(
            $this->playerDb,
            $this->nbRecords,
            $this->ordering
        );
    }

}