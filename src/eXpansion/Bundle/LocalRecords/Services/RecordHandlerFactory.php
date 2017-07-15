<?php

namespace eXpansion\Bundle\LocalRecords\Services;

use eXpansion\Bundle\LocalRecords\Repository\RecordRepository;


/**
 * Class RecordHandlerFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Services;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordHandlerFactory
{

    /** @var  RecordRepository */
    protected $recordRepository;

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
     * @param string           $ordering
     * @param int              $nbRecords
     * @param string           $className
     */
    public function __construct(RecordRepository $recordRepository, $ordering, $nbRecords, $className = RecordHandler::class)
    {
        $this->recordRepository = $recordRepository;
        $this->ordering = $ordering;
        $this->nbRecords = $nbRecords;
        $this->className = $className;
    }


    public function create()
    {
        $class = $this->className;

        return new $class($this->recordRepository, $this->nbRecords, $this->ordering);
    }

}