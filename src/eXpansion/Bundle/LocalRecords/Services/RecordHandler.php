<?php

namespace eXpansion\Bundle\LocalRecords\Services;

use eXpansion\Bundle\LocalRecords\Model\Map\RecordTableMap;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Model\RecordQueryBuilder;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\PlayersBundle\Model\Map\PlayerTableMap;
use eXpansion\Framework\PlayersBundle\Storage\PlayerDb;
use Propel\Runtime\Propel;

/**
 * Class RecordHandler
 *
 * @package eXpansion\Bundle\LocalRecords\Model;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordHandler
{
    /**
     * Available order logic for score.
     */
    const ORDER_ASC = "ASC";
    const ORDER_DESC = "DESC";

    /**
     * List of event types
     */
    const EVENT_TYPE_FIRST_TIME = 'first_time';
    const EVENT_TYPE_SAME_SCORE = 'same_score';
    const EVENT_TYPE_SAME_POS = 'same_position';
    const EVENT_TYPE_BETTER_POS = 'better_position';

    /**
     * List of data in the associative array returned.
     */
    const COL_EVENT = 'event';
    const COL_RECORD = 'record';
    const COL_OLD_RECORD = 'old_record';
    const COL_POS = 'position';
    const COL_OLD_POS = 'old_position';
    const COL_RECORDS = 'records';

    /** @var ConfigInterface */
    protected $nbRecords;

    /** @var string */
    protected $ordering;

    /** @var RecordQueryBuilder */
    protected $recordQueryBuilder;

    /** @var PlayerDb */
    protected $playerDb;

    /** @var Record[] */
    protected $records = [];

    /** @var Record[] */
    protected $recordsPerPlayer = [];

    /** @var int[] */
    protected $positionPerPlayer = [];

    /** @var int */
    protected $currentNbLaps;

    /** @var string */
    protected $currentMapUid;

    /**
     * RecordHandler constructor.
     *
     * @param RecordQueryBuilder $recordQueryBuilder
     * @param PlayerDb           $playerDb
     * @param ConfigInterface    $nbRecords
     * @param string             $ordering
     */
    public function __construct(
        RecordQueryBuilder $recordQueryBuilder,
        PlayerDb $playerDb,
        ConfigInterface $nbRecords,
        $ordering = self::ORDER_ASC
    ) {
        $this->recordQueryBuilder = $recordQueryBuilder;
        $this->nbRecords = $nbRecords;
        $this->ordering = $ordering;
        $this->playerDb = $playerDb;
    }

    /**
     * @return Record[]
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Get the position of a player
     *
     * @param string $login
     *
     * @return integer|null
     */
    public function getPlayerPosition($login)
    {
        return isset($this->positionPerPlayer[$login]) ? $this->positionPerPlayer[$login] : null;
    }

    /**
     * Get a players record information.
     *
     * @param $login
     *
     * @return Record|null
     */
    public function getPlayerRecord($login)
    {
        return isset($this->recordsPerPlayer[$login]) ? $this->recordsPerPlayer[$login] : null;
    }

    /**
     * Load records for a certain map.
     *
     * @param string  $mapUid
     * @param integer $nbLaps
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function loadForMap($mapUid, $nbLaps)
    {
        // Free old records from memory first.
        foreach ($this->records as $record) {
            $record->clearAllReferences(false);
            unset($record);
        }
        foreach ($this->recordsPerPlayer as $record) {
            $record->clearAllReferences(false);
            unset($record);
        }
        RecordTableMap::clearInstancePool();

        // Load them amm new.
        $this->recordsPerPlayer = [];
        $this->positionPerPlayer = [];

        $this->currentMapUid = $mapUid;
        $this->currentNbLaps = $nbLaps;

        $this->records = $this->recordQueryBuilder
            ->getMapRecords($mapUid, $nbLaps, $this->getScoreOrdering(), $this->nbRecords->get());

        $position = 1;
        foreach ($this->records as $record) {
            $this->recordsPerPlayer[$record->getPlayer()->getLogin()] = $record;
            $this->positionPerPlayer[$record->getPlayer()->getLogin()] = $position++;
        }
    }

    /**
     * Load records for certain players only.
     *
     * @param $mapUid
     * @param $nbLaps
     * @param $logins
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function loadForPlayers($mapUid, $nbLaps, $logins)
    {
        $logins = array_diff($logins, array_keys($this->recordsPerPlayer));

        if (!empty($logins)) {
            $records = $this->recordQueryBuilder->getPlayerMapRecords($mapUid, $nbLaps, $logins);

            foreach ($records as $record) {
                $this->recordsPerPlayer[$record->getPlayer()->getLogin()] = $record;
            }
        }
    }

    /**
     * Save all new records.
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function save()
    {

        $con = Propel::getWriteConnection(RecordTableMap::DATABASE_NAME);
        $con->beginTransaction();

        foreach ($this->recordsPerPlayer as $record) {
            $record->save();
        }

        $con->commit();

        RecordTableMap::clearInstancePool();
    }

    /**
     * Add a new record
     *
     * @param string $login
     * @param int    $score
     * @param int[]  $checkpoints
     *
     * @return array|null Data for the new records.
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function addRecord($login, $score, $checkpoints)
    {
        $oldPosition = isset($this->positionPerPlayer[$login]) ? $this->positionPerPlayer[$login] : count($this->records) + 1;
        $record = isset($this->recordsPerPlayer[$login]) ? $this->recordsPerPlayer[$login] : $this->getNewRecord($login);
        $this->recordsPerPlayer[$login] = $record;

        $oldRecord = clone $record;
        $this->updateRecordStats($record, $score);

        if (empty($this->records)) {
            $record->setScore($score);
            $record->setCreatedAt(new \DateTime());
            $record->setCheckpoints($checkpoints);

            $this->records[0] = $record;
            $this->positionPerPlayer[$record->getPlayer()->getLogin()] = 1;
            $this->recordsPerPlayer[$record->getPlayer()->getLogin()] = $record;

            return [
                self::COL_EVENT => self::EVENT_TYPE_FIRST_TIME,
                self::COL_RECORD => $record,
                self::COL_RECORDS => $this->records,
                self::COL_POS => 1,
            ];
        }

        // Check if first time of this player.
        $firstTime = is_null($record->getScore());

        if ($score == $record->getScore()) {
            return [
                self::COL_EVENT => self::EVENT_TYPE_SAME_SCORE,
                self::COL_RECORD => $record,
                self::COL_OLD_RECORD => $oldRecord,
                self::COL_RECORDS => $this->records,
            ];
        }

        if ($firstTime || $this->compareNewScore($record, $score)) {
            $recordIndex = $oldPosition - 1;
            $newPosition = $oldPosition;

            $this->records[$recordIndex] = $record;
            $this->positionPerPlayer[$record->getPlayer()->getLogin()] = $oldPosition;

            while ($recordIndex > 0 && $this->compareNewScore($this->records[$recordIndex - 1], $score)) {
                $previousRecord = $this->records[$recordIndex - 1];

                $this->records[$recordIndex - 1] = $record;
                $this->records[$recordIndex] = $previousRecord;

                $newPosition = $recordIndex;
                $this->positionPerPlayer[$record->getPlayer()->getLogin()] = $recordIndex;
                $this->positionPerPlayer[$previousRecord->getPlayer()->getLogin()] = $recordIndex + 1;

                $recordIndex--;
            }

            $record->setScore($score);
            $record->setUpdatedAt(new \DateTime());
            $record->setCheckpoints($checkpoints);

            // Remove entries whose position is superior to the limit.
            $this->records = array_slice($this->records, 0, $this->nbRecords->get());

            if ($newPosition <= $this->nbRecords->get()) {
                if ($newPosition != $oldPosition || $firstTime) {
                    return [
                        self::COL_EVENT => self::EVENT_TYPE_BETTER_POS,
                        self::COL_RECORD => $record,
                        self::COL_OLD_RECORD => $oldRecord,
                        self::COL_RECORDS => $this->records,
                        self::COL_POS => $newPosition,
                        self::COL_OLD_POS => $firstTime ? null : $oldPosition,
                    ];
                }

                return [
                    self::COL_EVENT => self::EVENT_TYPE_SAME_POS,
                    self::COL_RECORD => $record,
                    self::COL_OLD_RECORD => $oldRecord,
                    self::COL_RECORDS => $this->records,
                    self::COL_POS => $newPosition,
                ];
            }
        }

        return null;
    }

    /**
     * Get a new record instance.
     *
     * @param string $login
     *
     * @return Record
     */
    protected function getNewRecord($login)
    {
        $record = new Record();
        $record->setPlayer($this->playerDb->get($login));
        $record->setNbLaps($this->currentNbLaps);
        $record->setNbFinish(0);
        $record->setMapUid($this->currentMapUid);

        return $record;
    }

    /**
     * Update Records statistics.
     *
     * @param Record         $record
     * @param        integer $score
     */
    protected function updateRecordStats(Record $record, $score)
    {
        $record->setAvgScore(
            (($record->getAvgScore() * $record->getNbFinish()) + $score) / ($record->getNbFinish() + 1)
        );
        $record->setNbFinish($record->getNbFinish() + 1);
    }


    /**
     * Get ordering use for sorting.
     *
     * @return string
     */
    protected function getScoreOrdering()
    {
        return $this->ordering;
    }

    /**
     * @param int    $newScore
     * @param Record $record
     *
     * @return bool
     */
    protected function compareNewScore($record, $newScore)
    {
        if ($this->getScoreOrdering() == self::ORDER_ASC) {
            return $newScore <= $record->getScore();
        } else {
            return $newScore >= $record->getScore();
        }
    }
}
