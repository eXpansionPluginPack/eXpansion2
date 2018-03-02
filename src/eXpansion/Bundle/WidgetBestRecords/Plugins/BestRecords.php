<?php

namespace eXpansion\Bundle\WidgetBestRecords\Plugins;

use eXpansion\Bundle\Dedimania\DataProviders\Listener\DedimaniaDataListener;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\WidgetBestRecords\Plugins\Gui\BestRecordsWidgetFactory;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;


class BestRecords implements StatusAwarePluginInterface, RecordsDataListener, DedimaniaDataListener, ListenerInterfaceMpLegacyMap
{
    /** @var Factory */
    protected $factory;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var BestRecordsWidgetFactory
     */
    private $widget;
    /**
     * @var Group
     */
    private $players;

    /**
     * @var Group
     */
    private $allPlayers;


    /**
     * Debug constructor.
     *
     * @param Connection               $connection
     * @param PlayerStorage            $playerStorage
     * @param BestRecordsWidgetFactory $widget
     * @param Group                    $players
     * @param Group                    $allPlayers
     */
    public function __construct(
        Factory $factory,
        PlayerStorage $playerStorage,
        BestRecordsWidgetFactory $widget,
        Group $players,
        Group $allPlayers
    ) {
        $this->factory = $factory;
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
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
            $map = $this->factory->getConnection()->getCurrentMapInfo();
            $this->widget->setAuthorTime($map->author, $map->goldTime);
            $this->widget->create($this->allPlayers);
        } else {
            $this->widget->destroy($this->allPlayers);
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
            $this->widget->setLocalRecord($records[0]);
        } else {
            $this->widget->setLocalRecord(null);
        }
        $this->widget->update($this->allPlayers);
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
        $this->widget->setLocalRecord($record);
        $this->widget->update($this->allPlayers);
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
            $this->widget->setLocalRecord($record);
            $this->widget->update($this->allPlayers);
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
            $this->widget->setLocalRecord($record);
            $this->widget->update($this->allPlayers);
        }
    }


    /**
     * @param Map $map
     *
     * @return void
     */
    public function onBeginMap(Map $map)
    {
        $this->widget->setAuthorTime($map->author, $map->authorTime);
    }

    /**
     * @param Map $map
     *
     * @return void
     */
    public function onEndMap(Map $map)
    {

    }

    /**
     * Called when dedimania records are loaded.
     *
     * @param DedimaniaRecord[] $records
     */
    public function onDedimaniaRecordsLoaded($records)
    {
        print_r($records);

        if (count($records) > 0) {


            $this->widget->setDedimaniaRecord($records[0]);
        } else {
            $this->widget->setDedimaniaRecord(null);
        }
        $this->widget->update($this->allPlayers);
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
            $this->widget->setDedimaniaRecord($record);
            $this->widget->update($this->allPlayers);
        }
    }

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerConnect(DedimaniaPlayer $player)
    {
        // TODO: Implement onDedimaniaPlayerConnect() method.
    }

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerDisconnect(DedimaniaPlayer $player)
    {
        // TODO: Implement onDedimaniaPlayerDisconnect() method.
    }
}
