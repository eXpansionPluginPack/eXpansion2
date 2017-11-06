<?php

namespace eXpansion\Bundle\LocalRecords\Services;

use eXpansion\Bundle\LocalRecords\Repository\RecordRepository;
use eXpansion\Framework\PlayersBundle\Storage\PlayerDb;


/**
 * Class RecordHandlerFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Services;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordHandlerFactory
{
    /** @var RecordRepository */
    protected $recordRepository;

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
     * @param RecordRepository $recordRepository
     * @param PlayerDb         $playerDb
     * @param string           $ordering
     * @param int              $nbRecords
     * @param string           $className
     */
    public function __construct(
        RecordRepository $recordRepository,
        PlayerDb $playerDb,
        $ordering,
        $nbRecords,
        $className = RecordHandler::class
    ) {
        $this->recordRepository = $recordRepository;
        $this->playerDb = $playerDb;
        $this->ordering = $ordering;
        $this->nbRecords = $nbRecords;
        $this->className = $className;
    }


    public function create()
    {
        $class = $this->className;

        return new $class(
            $this->recordRepository,
            $this->playerDb,
            $this->nbRecords,
            $this->ordering
        );
    }

}