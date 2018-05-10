<?php

namespace eXpansionExperimantal\Bundle\WidgetRecords\Plugins;

use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Plugins\BaseRecords;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use eXpansionExperimantal\Bundle\Dedimania\DataProviders\Listener\DedimaniaDataListener;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansionExperimantal\Bundle\WidgetLiveRankings\Plugins\Gui\LiveRankingsWidgetFactory;
use eXpansionExperimantal\Bundle\WidgetRecords\Plugins\Gui\RecordsWidgetFactory;


class Records implements StatusAwarePluginInterface, RecordsDataListener, DedimaniaDataListener, ListenerInterfaceMpScriptMatch, ListenerInterfaceMpScriptPodium
{
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var LiveRankingsWidgetFactory
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
     * @param PlayerStorage        $playerStorage
     * @param RecordsWidgetFactory $widget
     * @param Group                $players
     * @param Group                $allPlayers
     */
    public function __construct(
        PlayerStorage $playerStorage,
        RecordsWidgetFactory $widget,
        Group $players,
        Group $allPlayers
    ) {

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
            $this->widget->create($this->allPlayers);
        }
    }

    /**
     * Called when dedimania records are loaded.
     *
     * @param DedimaniaRecord[] $records
     */
    public function onDedimaniaRecordsLoaded($records)
    {
        $this->widget->setDedimaniaRecords($records);
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
        $this->widget->setDedimaniaRecords($records);
        $this->widget->update($this->allPlayers);
    }

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerConnect(DedimaniaPlayer $player)
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

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerDisconnect(DedimaniaPlayer $player)
    {

    }

    /**
     * Called when local records are loaded.
     *
     * @param Record[]    $records
     * @param BaseRecords $baseRecords
     */
    public function onLocalRecordsLoaded($records, BaseRecords $baseRecords)
    {
        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

        $this->widget->setLocalRecords($records);
        $this->widget->update($this->allPlayers);

    }

    /**
     * Called when a player finishes map for the very first time (basically first record).
     *
     * @param Record        $record
     * @param Record[]      $records
     * @param               $position
     * @param BaseRecords   $baseRecords
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position, BaseRecords $baseRecords)
    {
        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

        $this->widget->setLocalRecords($records);
        $this->widget->update($this->allPlayers);
    }

    /**
     * Called when a player finishes map and does same time as before.
     *
     * @param Record      $record
     * @param Record      $oldRecord
     * @param Record[]    $records
     * @param BaseRecords $baseRecords
     */
    public function onLocalRecordsSameScore(Record $record, Record $oldRecord, $records, BaseRecords $baseRecords)
    {
        //
    }

    /**
     * Called when a player finishes map with better time and has better position.
     *
     * @param Record      $record
     * @param Record      $oldRecord
     * @param Record[]    $records
     * @param int         $position
     * @param int         $oldPosition
     * @param BaseRecords $baseRecords
     */
    public function onLocalRecordsBetterPosition(
        Record $record,
        Record $oldRecord,
        $records,
        $position,
        $oldPosition,
        BaseRecords $baseRecords
    ) {

        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

        $this->widget->setLocalRecords($records);
        $this->widget->update($this->allPlayers);
    }

    /**
     * Called when a player finishes map with better time but keeps same position.
     *
     * @param Record        $record
     * @param Record        $oldRecord
     * @param Record[]      $records
     * @param               $position
     * @param BaseRecords   $baseRecords
     */
    public function onLocalRecordsSamePosition(
        Record $record,
        Record $oldRecord,
        $records,
        $position,
        BaseRecords $baseRecords
    ) {
        if (!$this->checkRecordPlugin($baseRecords)) {
            return;
        }

        $this->widget->setLocalRecords($records);
        $this->widget->update($this->allPlayers);
    }

    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchStart($count, $time)
    {
        $this->widget->create($this->allPlayers);
    }

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartMatchEnd($count, $time)
    {

    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchStart($count, $time)
    {
        $this->widget->destroy($this->allPlayers);
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndMatchEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartTurnEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndTurnEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onStartRoundEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundStart($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onEndRoundEnd($count, $time)
    {
        //
    }

    /**
     * Callback sent when the "onPodiumStart" section start.
     *
     * @param int $time Server time when the callback was sent
     * @return void
     */
    public function onPodiumStart($time)
    {
        $this->widget->destroy($this->allPlayers);
    }

    /**
     * Callback sent when the "onPodiumEnd" section end.
     *
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onPodiumEnd($time)
    {
        // TODO: Implement onPodiumEnd() method.
    }
}
