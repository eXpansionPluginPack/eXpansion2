<?php

namespace eXpansion\Bundle\WidgetBestCheckpoints\Plugins;

use eXpansion\Bundle\Dedimania\DataProviders\Listener\DedimaniaDataListener;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui\BestCheckpointsWidgetFactory;
use eXpansion\Bundle\WidgetBestCheckpoints\Plugins\Gui\UpdaterWidgetFactory;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use Maniaplanet\DedicatedServer\Structures\Map;


class BestCheckpoints implements StatusAwarePluginInterface, RecordsDataListener, DedimaniaDataListener, ListenerInterfaceMpLegacyMap
{
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var BestCheckpointsWidgetFactory
     */
    private $widget;
    /**
     * @var Group
     */
    private $players;
    /**
     * @var UpdaterWidgetFactory
     */
    private $updater;
    /**
     * @var Group
     */
    private $allPlayers;


    /**
     * BestCheckpoints constructor.
     *
     * @param Factory                      $factory
     * @param PlayerStorage                $playerStorage
     * @param BestCheckpointsWidgetFactory $widget
     * @param UpdaterWidgetFactory         $updater
     * @param Group                        $players
     * @param Group                        $allPlayers
     */
    public function __construct(
        PlayerStorage $playerStorage,
        BestCheckPointsWidgetFactory $widget,
        UpdaterWidgetFactory $updater,
        Group $players,
        Group $allPlayers
    ) {
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
        $this->updater = $updater;
        $this->allPlayers = $allPlayers;
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->widget->create($this->players);
            $this->updater->create($this->allPlayers);
        } else {
            $this->widget->destroy($this->players);
            $this->updater->destroy($this->allPlayers);
        }
    }

    /**
     * Called when local records are loaded.
     *
     * @param Record[] $records
     */
    public function onLocalRecordsLoaded($records)
    {
        if (count($records) > 0) {
            $this->updater->setLocalRecord($records[0]->getCheckpoints());
        } else {
            $this->updater->setLocalRecord([]);
        }
    }

    /**
     * Called when a player finishes map for the very first time (basically first record).
     *
     * @param Record   $record
     * @param Record[] $records
     * @param          $position
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position)
    {
        $this->updater->setLocalRecord($record->getCheckpoints());
    }

    /**
     * Called when a player finishes map and does same time as before.
     *
     * @param Record   $record
     * @param Record   $oldRecord
     * @param Record[] $records
     */
    public function onLocalRecordsSameScore(Record $record, Record $oldRecord, $records)
    {

    }

    /**
     * Called when a player finishes map with better time and has better position.
     *
     * @param Record   $record
     * @param Record   $oldRecord
     * @param Record[] $records
     * @param int      $position
     * @param int      $oldPosition
     */
    public function onLocalRecordsBetterPosition(Record $record, Record $oldRecord, $records, $position, $oldPosition)
    {
        if ($position == 1) {
            $this->updater->setLocalRecord($record->getCheckpoints());
        }
    }

    /**
     * Called when a player finishes map with better time but keeps same position.
     *
     * @param Record   $record
     * @param Record   $oldRecord
     * @param Record[] $records
     * @param          $position
     */
    public function onLocalRecordsSamePosition(Record $record, Record $oldRecord, $records, $position)
    {
        if ($position == 1) {
            $this->updater->setLocalRecord($record->getCheckpoints());
        }
    }


    /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map)
    {

    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onEndMap(Map $map)
    {
        $this->updater->setLocalRecord([]);
    }

    /**
     * Called when dedimania records are loaded.
     *
     * @param DedimaniaRecord[] $records
     */
    public function onDedimaniaRecordsLoaded($records)
    {
        if (count($records) > 1) {
            $this->updater->setDedimaniaRecord($records[0]->getCheckpoints());
        } else {
            $this->updater->setDedimaniaRecord([]);
        }
    }

    /**
     * @param DedimaniaRecord   $record
     * @param DedimaniaRecord   $oldRecord
     * @param DedimaniaRecord[] $records
     * @param  int              $position
     * @param  int              $oldPosition
     * @return void
     */
    public function onDedimaniaRecordsUpdate(
        DedimaniaRecord $record,
        DedimaniaRecord $oldRecord,
        $records,
        $position,
        $oldPosition
    ) {
        if ($position == 1) {
            $this->updater->setDedimaniaRecord($record->getCheckpoints());
        }
    }

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerConnect(DedimaniaPlayer $player)
    {
        // do nothing
    }

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerDisconnect(DedimaniaPlayer $player)
    {
        // do nothing
    }


}
