<?php


namespace eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class AllRecords
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class AllRecords implements RecordsDataListener, ListenerInterfaceMpLegacyMap
{
    /** @var RecordHandler[] */
    protected $recordHandlers;

    public function getMapRecords()
    {
        $mergedRecords = [];

        if (!isset($this->recordHandlers[1])) {
            return $mergedRecords;
        }

        foreach ($this->recordHandlers[1]->getRecords() as $i => $record) {
            $recordData = [
                'position' => $i + 1,
                'player' => $record->getPlayer(),
                'record' => ['1' => $record],
            ];

            foreach ($this->recordHandlers as $nbLaps => $recordHandler) {
                if ($nbLaps != 1) {
                    $position = $recordHandler->getPlayerPosition($record->getPlayer()->getLogin());
                    if ($position !== null) {
                        $recordData["record"][$nbLaps] = $recordHandler->getPlayerRecord($record->getPlayer()->getLogin());
                        $recordData["record"]["other"] = $recordHandler->getPlayerRecord($record->getPlayer()->getLogin());
                    }
                }
            }

            $mergedRecords[] = $recordData;
        }

        return $mergedRecords;
    }

    /**
     * @inheritdoc
     */
    public function onBeginMap(Map $map)
    {
        $this->recordHandlers = [];
    }

    /**
     * @inheritdoc
     */
    public function onLocalRecordsLoaded($records, BaseRecords $baseRecords)
    {
        $this->recordHandlers[$baseRecords->getRecordsHandler()->getCurrentNbLaps()] = $baseRecords->getRecordsHandler();
    }

    /**
     * @inheritdoc
     */
    public function onEndMap(Map $map)
    {
    }

    /**
     * @inheritdoc
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position, BaseRecords $baseRecords)
    {
    }

    /**
     * @inheritdoc
     */
    public function onLocalRecordsSameScore(Record $record, Record $oldRecord, $records, BaseRecords $baseRecords)
    {
    }

    /**
     * @inheritdoc
     */
    public function onLocalRecordsBetterPosition(
        Record $record,
        Record $oldRecord,
        $records,
        $position,
        $oldPosition,
        BaseRecords $baseRecords
    ) {
    }

    /**
     * @inheritdoc
     */
    public function onLocalRecordsSamePosition(
        Record $record,
        Record $oldRecord,
        $records,
        $position,
        BaseRecords $baseRecords
    ) {
    }
}