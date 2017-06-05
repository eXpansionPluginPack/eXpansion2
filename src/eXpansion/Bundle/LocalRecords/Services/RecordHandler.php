<?php

namespace eXpansion\Bundle\LocalRecords\Services;
use eXpansion\Bundle\LocalRecords\Entity\Record;
use eXpansion\Bundle\LocalRecords\Repository\RecordRepository;

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
    const EVENT_TYPE_SAME_POS =   'same_position';
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

    /** @var int */
    protected $nbRecords;

    /** @var string */
    protected $ordering;

    /** @var  RecordRepository */
    protected $recordRepository;

    /** @var Record[] */
    protected $records;

    /** @var Record[] */
    protected $recordsPerPlayer;

    /** @var int[] */
    protected $positionPerPlayer;

    /** @var int */
    protected $currentNbLaps;

    protected $currentMapUid;

    /**
     * RaceRecordHandler constructor.
     *
     * @param RecordRepository $recordRepository
     * @param int $nbRecords
     */
    public function __construct(RecordRepository $recordRepository, $nbRecords, $ordering = self::ORDER_ASC)
    {
        $this->recordRepository = $recordRepository;
        $this->nbRecords = $nbRecords;
        $this->ordering = $ordering;
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
     * @return bool
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
     * @param $mapUid
     * @param $nbLaps
     */
    public function loadForMap($mapUid, $nbLaps)
    {
        $this->recordsPerPlayer = [];
        $this->positionPerPlayer = [];

        $this->currentMapUid = $mapUid;
        $this->currentNbLaps = $nbLaps;

        $this->records = $this->recordRepository->findBy(
            ['mapUid' => $mapUid, 'nbLaps' => $nbLaps],
            ['score' => $this->getScoreOrdering()],
            $this->nbRecords
        );

        $position = 1;
        foreach ($this->records as $record)
        {
            $this->recordsPerPlayer[$record->getPlayerLogin()] = $record;
            $this->positionPerPlayer[$record->getPlayerLogin()] = $position++;
        }
    }

    /**
     * Load records for certain players only.
     *
     * @param $mapUid
     * @param $nbLaps
     * @param $logins
     */
    public function loadForPlayers($mapUid, $nbLaps, $logins)
    {
        $logins = array_diff(array_keys($this->recordsPerPlayer), $logins);

        if (!empty($logins)) {
            $records = $this->recordRepository->findBy(
                ['mapUid' => $mapUid, 'nbLaps' => $nbLaps, 'playerLogin' => $logins],
                ['score' => $this->getScoreOrdering()],
                $this->nbRecords
            );

            foreach ($records as $record) {
                $this->recordsPerPlayer[$record->getPlayerLogin()] = $record;
            }
        }
    }

    /**
     * Save all new records.
     */
    public function save()
    {
        $this->recordRepository->massSave($this->recordsPerPlayer);
    }

    /**
     * Add a new record
     *
     * @param string $login
     * @param int $score
     * @param int[] $checkpoints
     *
     * @return array|null Data for the new records.
     */
    public function addRecord($login, $score, $checkpoints) {
        $oldPosition = isset($this->positionPerPlayer[$login]) ? $this->positionPerPlayer[$login] : count($this->records) + 1;
        $record = isset($this->recordsPerPlayer[$login]) ? $this->recordsPerPlayer[$login] : $this->getNewRecord($login);

        $oldRecord = clone $record;
        $this->updateRecordStats($record, $score);

        if (empty($this->records)) {
            $record->setScore($score);
            $record->setDate(new \DateTime());
            $record->setCheckpointTimes($checkpoints);

            $this->records[0] = $record;
            $this->positionPerPlayer[$record->getPlayerLogin()] = 1;
            $this->recordsPerPlayer[$record->getPlayerLogin()] = $record;

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
                self::COL_RECORDS => $this->records
            ];
        }

        if ($firstTime || $this->compareNewScore($record, $score)) {

            $betterRecordIndex = $oldPosition - 2;
            $newPosition = $oldPosition;

            while ($betterRecordIndex >= 0 && $this->compareNewScore($this->records[$betterRecordIndex], $score)) {
                $previousRecord = $this->records[$betterRecordIndex];

                $this->records[$betterRecordIndex] = $record;
                $this->records[$betterRecordIndex+1] = $previousRecord;

                $newPosition = $betterRecordIndex + 1;
                $this->positionPerPlayer[$record->getPlayerLogin()] = $betterRecordIndex + 1;
                $this->positionPerPlayer[$previousRecord->getPlayerLogin()] = $betterRecordIndex + 2;

                $betterRecordIndex--;
            }

            $record->setScore($score);
            $record->setDate(new \DateTime());
            $record->setCheckpointTimes($checkpoints);

            // Remove entries whose position is superior to the limit.
            $this->records = array_slice($this->records, 0, $this->nbRecords);

            if ($newPosition <= $this->nbRecords) {
                if ($newPosition != $oldPosition) {
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
     * @param $login
     *
     * @return Record
     */
    protected function getNewRecord($login)
    {
        $record = new Record();
        $record->setPlayerLogin($login);
        $record->setNbLaps($this->currentNbLaps);
        $record->setNbFinish(0);
        $record->setMapUid($this->currentMapUid);

        return $record;
    }

    /**
     * Update Records statistics.
     *
     * @param Record $record
     * @param        $score
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
     * @param int $newScore
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