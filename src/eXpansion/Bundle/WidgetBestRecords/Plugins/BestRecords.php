<?php

namespace eXpansion\Bundle\WidgetBestRecords\Plugins;

use eXpansion\Bundle\Dedimania\DataProviders\Listener\DedimaniaDataListener;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaPlayer;
use eXpansion\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\WidgetBestRecords\Plugins\Gui\BestRecordsWidget;
use eXpansion\Bundle\WidgetBestRecords\Plugins\Gui\PBWidget;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMatch;

class BestRecords implements StatusAwarePluginInterface, RecordsDataListener, DedimaniaDataListener, ListenerInterfaceMpScriptMatch, ListenerInterfaceMpLegacyPlayer
{
    /** @var Factory */
    protected $factory;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var BestRecordsWidget
     */
    private $widget;
    /**
     * @var Group
     */
    private $players;

    /** @var Group[] */
    private $widgetGroups;

    /**
     * @var Group
     */
    private $allPlayers;
    /**
     * @var PBWidget
     */
    private $PBWidget;

    /**
     * @var MapStorage
     */
    private $mapStorage;

    /**
     * Debug constructor.
     *
     * @param MapStorage        $mapStorage
     * @param PlayerStorage     $playerStorage
     * @param BestRecordsWidget $widget
     * @param PBWidget          $PBWidget
     * @param Group             $players
     * @param Group             $allPlayers
     */
    public function __construct(
        MapStorage $mapStorage,
        PlayerStorage $playerStorage,
        BestRecordsWidget $widget,
        PBWidget $PBWidget,
        Group $players,
        Group $allPlayers
    ) {
        $this->playerStorage = $playerStorage;
        $this->widget = $widget;
        $this->players = $players;
        $this->allPlayers = $allPlayers;
        $this->PBWidget = $PBWidget;
        $this->mapStorage = $mapStorage;
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
            $map = $this->mapStorage->getCurrentMap();
            $this->setAuthorTime($map->authorTime);

            foreach ($this->allPlayers->getLogins() as $login) {
                $this->widgetGroups[$login] = $this->PBWidget->create($login);
            }

            $this->widget->create($this->allPlayers);
        } else {
            $this->widget->destroy($this->allPlayers);
        }
    }

    /**
     * Called when local records are loaded.
     *
     * @param Record[] $records
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function onLocalRecordsLoaded($records)
    {
        if (count($records) > 0) {
            $this->widget->setLocalRecord($records[0]);
        } else {
            $this->widget->setLocalRecord(null);
        }

        foreach ($records as $record) {
            $login = $record->getPlayer()->getLogin();
            // set records to widget
            $this->PBWidget->setPB($login, $record->getScore());
        }

        // update for all players on server
        $this->updateAuthorPB(null);
        $this->widget->update($this->allPlayers);
    }

    /**
     * Called when a player finishes map for the very first time (basically first record).
     *
     * @param Record   $record
     * @param Record[] $records
     * @param          $position
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position)
    {
        $this->widget->setLocalRecord($record);
        $this->widget->update($this->allPlayers);
        $this->updateAuthorPB($record->getPlayer()->getLogin(), $record->getScore());
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
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function onLocalRecordsBetterPosition(Record $record, Record $oldRecord, $records, $position, $oldPosition)
    {
        if ($position == 1) {
            $this->widget->setLocalRecord($record);
            $this->widget->update($this->allPlayers);
        }
        $this->updateAuthorPB($record->getPlayer()->getLogin(), $record->getScore());
    }

    /**
     * Called when a player finishes map with better time but keeps same position.
     *
     * @param Record   $record
     * @param Record   $oldRecord
     * @param Record[] $records
     * @param          $position
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function onLocalRecordsSamePosition(Record $record, Record $oldRecord, $records, $position)
    {
        if ($position == 1) {
            $this->widget->setLocalRecord($record);
            $this->widget->update($this->allPlayers);
        }
        $this->updateAuthorPB($record->getPlayer()->getLogin(), $record->getScore());

    }

    /**
     * @param mixed $authorTime
     */
    public function setAuthorTime($authorTime)
    {
        $this->PBWidget->setAuthorTime($authorTime);
    }

    private function updateAuthorPB($login, $pbTime = null)
    {
        if ($login == null) {
            foreach ($this->widgetGroups as $group) {
                if ($pbTime) {
                    $this->PBWidget->setPB($login, $pbTime);
                }
                $this->PBWidget->update($group);
            }
        } else {
            if ($pbTime) {
                $this->PBWidget->setPB($login, $pbTime);
            }
            if (isset($this->widgetGroups[$login])) {
                $this->PBWidget->update($this->widgetGroups[$login]);
            }

        }
    }

    /**
     * Called when dedimania records are loaded.
     *
     * @param DedimaniaRecord[] $records
     */
    public function onDedimaniaRecordsLoaded($records)
    {
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
        //
    }

    /**
     * @param DedimaniaPlayer $player
     * @return void
     */
    public function onDedimaniaPlayerDisconnect(DedimaniaPlayer $player)
    {
        //
    }

    /**
     * @param Player $player
     * @return void
     */
    public function onPlayerConnect(Player $player)
    {
        $this->widgetGroups[$player->getLogin()] = $this->PBWidget->create($player->getLogin());
    }

    /**
     * @param Player $player
     * @param string $disconnectionReason
     * @return void
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        if (isset($this->widgetGroups[$player->getLogin()])) {
            unset($this->widgetGroups[$player->getLogin()]);
        }
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        //
    }

    /**
     * @param Player $oldPlayer
     * @param Player $player
     * @return void
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        //
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
        $this->PBWidget->reset();
        $map = $this->mapStorage->getCurrentMap();
        $this->PBWidget->setAuthorTime($map->authorTime);
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
    public function onEndMatchStart($count, $time)
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
}
