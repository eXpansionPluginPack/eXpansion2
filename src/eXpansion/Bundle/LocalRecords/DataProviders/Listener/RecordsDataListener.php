<?php

namespace eXpansion\Bundle\LocalRecords\DataProviders\Listener;

use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Plugins\BaseRecords;

/**
 * Interface RecordsDataListener
 *
 * @package eXpansion\Bundle\LocalRecords\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface RecordsDataListener
{
    /**
     * Called when local records are loaded.
     *
     * @param Record[] $records
     */
    public function onLocalRecordsLoaded($records, BaseRecords $baseRecords);

    /**
     * Called when a player finishes map for the very first time (basically first record).
     *
     * @param Record        $record
     * @param Record[]      $records
     * @param               $position
     * @param BaseRecords $baseRecords
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position, BaseRecords $baseRecords);

    /**
     * Called when a player finishes map and does same time as before.
     *
     * @param Record        $record
     * @param Record        $oldRecord
     * @param Record[]      $records
     * @param BaseRecords $baseRecords
     */
    public function onLocalRecordsSameScore(Record $record, Record $oldRecord, $records, BaseRecords $baseRecords);

    /**
     * Called when a player finishes map with better time and has better position.
     *
     * @param Record        $record
     * @param Record        $oldRecord
     * @param Record[]      $records
     * @param int           $position
     * @param int           $oldPosition
     * @param BaseRecords $baseRecords
     */
    public function onLocalRecordsBetterPosition(Record $record, Record $oldRecord, $records, $position, $oldPosition, BaseRecords $baseRecords);

    /**
     * Called when a player finishes map with better time but keeps same position.
     *
     * @param Record        $record
     * @param Record        $oldRecord
     * @param Record[]      $records
     * @param               $position
     * @param BaseRecords $baseRecords
     */
    public function onLocalRecordsSamePosition(Record $record, Record $oldRecord, $records, $position, BaseRecords $baseRecords);
}