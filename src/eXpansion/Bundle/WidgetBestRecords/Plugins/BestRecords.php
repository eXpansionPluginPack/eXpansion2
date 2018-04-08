<?php

namespace eXpansion\Bundle\WidgetBestRecords\Plugins;

use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Plugins\BaseRecords;
use eXpansion\Bundle\WidgetBestRecords\Plugins\Gui\BestRecordsWidgetFactory;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;


class BestRecords implements StatusAwarePluginInterface, RecordsDataListener, ListenerInterfaceMpLegacyMap
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
     * @inheritdoc
     */
    public function onLocalRecordsLoaded($records, BaseRecords $baseRecords)
    {
        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

        if (count($records) > 0) {
            $this->widget->setLocalRecord($records[0]);
        } else {
            $this->widget->setLocalRecord(null);
        }
        $this->widget->update($this->allPlayers);
    }

    /**
     * @inheritdoc
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position, BaseRecords $baseRecords)
    {
        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

        $this->widget->setLocalRecord($record);
        $this->widget->update($this->allPlayers);
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
    public function onLocalRecordsBetterPosition(Record $record, Record $oldRecord, $records, $position, $oldPosition, BaseRecords $baseRecords)
    {
        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

        if ($position == 1) {
            $this->widget->setLocalRecord($record);
            $this->widget->update($this->allPlayers);
        }
    }

    /**
     * @inheritdoc
     */
    public function onLocalRecordsSamePosition(Record $record, Record $oldRecord, $records, $position, BaseRecords $baseRecords)
    {
        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

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
     * Check if we can use the data for this plugin.
     *
     * @param BaseRecords $baseRecords
     *
     * @return bool
     */
    protected function checkRecordPlugin(BaseRecords $baseRecords)
    {
        return $baseRecords->getRecordsHandler()->getCurrentNbLaps() == 1;
    }
}
