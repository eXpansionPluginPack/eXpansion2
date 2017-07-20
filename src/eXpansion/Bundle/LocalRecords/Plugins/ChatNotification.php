<?php

namespace eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Framework\Core\Helpers\ChatNotification as ChatNotificationHelper;
use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Entity\Record;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;


/**
 * Class ChatNotificaiton
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ChatNotification implements RecordsDataListener
{
    /** @var ChatNotificationHelper */
    protected $chatNotification;

    /** @var  PlayerStorage */
    protected $playerStorage;

    /** @var Time */
    protected $timeFormater;

    /** @var string */
    protected $translationPrefix;

    /** @var int */
    protected $positionForPublicMessage;

    /**
     * ChatNotification constructor.
     *
     * @param ChatNotificationHelper $chatNotification
     * @param PlayerStorage $playerStorage
     * @param Time $timeFormater
     * @param string $translationPrefix
     * @param int $positionForPublicMessage
     */
    public function __construct(
        ChatNotificationHelper $chatNotification,
        PlayerStorage $playerStorage,
        Time $timeFormater,
        $translationPrefix,
        $positionForPublicMessage
    ) {
        $this->chatNotification = $chatNotification;
        $this->playerStorage = $playerStorage;
        $this->timeFormater = $timeFormater;
        $this->translationPrefix = $translationPrefix;
        $this->positionForPublicMessage = $positionForPublicMessage;
    }

    /**
     * Called when local records are loaded.
     *
     * @param Record[] $records
     */
    public function onLocalRecordsLoaded($records)
    {
        if (!empty($records)) {
            $firstRecord = $records[0];

            $this->sendMessage('loaded.top1', null, [
                '%nickname%' => $firstRecord->getPlayerLogin(), // TODO get player nickname from database.
                '%score%' => $this->timeFormater->timeToText($firstRecord->getScore(), true),
            ]);


            $onlinePlayers = $this->playerStorage->getOnline();
            $count = count($records);
            for ($i = 1; $i < $count; $i++) {
                if (isset($onlinePlayers[$records[$i]->getPlayerLogin()])) {
                    $this->sendMessage('loaded.any', $records[$i]->getPlayerLogin(), [
                        '%nickname%' => $onlinePlayers[$records[$i]->getPlayerLogin()]->getNickName(),
                        '%score%' => $this->timeFormater->timeToText($records[$i]->getScore(), true),
                        '%position%' => $i + 1,
                    ]);
                }
            }
        }
    }

    /**
     * Called when a player finishes map for the very first time (basically first record).
     *
     * @param Record     $record
     * @param Record[]   $records
     * @param int        $position
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position)
    {
        $this->messageFirstPlaceNew($record);
    }

    /**
     * Called when a player finishes map and does same time as before.
     *
     * @param Record    $record
     * @param Record    $oldRecord
     * @param Record[]  $records
     *
     * @return void
     */
    public function onLocalRecordsSameScore(Record $record, Record $oldRecord, $records)
    {
        // Nothing.
    }

    /**
     * Called when a player finishes map with better time and has better position.
     *
     * @param Record    $record
     * @param Record    $oldRecord
     * @param Record[]  $records
     * @param int       $position
     * @param int       $oldPosition
     *
     * @return void
     */
    public function onLocalRecordsBetterPosition(Record $record, Record $oldRecord, $records, $position, $oldPosition)
    {
        if ($position == 1 && $oldPosition == null) {
            $this->messageFirstPlaceNew($record);
            return;
        }

        // Check to who to send.
        $to = null;
        if ($position > $this->positionForPublicMessage) {
            $to = $record->getPlayerLogin();
        }

        // Check which message to send.
        $msg = 'better';
        if ($oldPosition == null) {
            $msg = 'new';
        }

        // Check for top status
        if ($position == 1) {
            $msg .= '.top1';
        } else {
            if ($position <= 5) {
                $msg .= '.top5';
            } else {
                $msg .= '.any';
            }
        }

        $securedBy = $this->getSecuredBy($record, $oldRecord);
        $this->sendMessage(
            $msg,
            $to,
            [
                '%nickname%' => $this->playerStorage->getPlayerInfo($record->getPlayerLogin())->getNickName(),
                '%score%' => $this->timeFormater->timeToText($record->getScore(), true),
                '%position%' => $position,
                '%old_position%' => $oldPosition,
                '%by%' => $securedBy,
            ]
        );
    }

    /**
     * Called when a player finishes map with better time but keeps same position.
     *
     * @param Record $record
     * @param Record $oldRecord
     * @param Record[] $records
     * @param          $position
     *
     * @return void
     */
    public function onLocalRecordsSamePosition(Record $record, Record $oldRecord, $records, $position)
    {
        // Check to who to send.
        $to = null;
        if ($position > $this->positionForPublicMessage) {
            $to = $record->getPlayerLogin();
        }

        // Check which message to send.
        $msg = 'secures';
        if ($position == 1) {
            $msg .= '.top1';
        } else {
            if ($position <= 5) {
                $msg .= '.top5';
            } else {
                $msg .= '.any';
            }
        }

        $securedBy = $this->getSecuredBy($record, $oldRecord);
        $this->sendMessage(
            $msg,
            $to,
            [
                '%nickname%' => $this->playerStorage->getPlayerInfo($record->getPlayerLogin())->getNickName(),
                '%score%' => $this->timeFormater->timeToText($record->getScore(), true),
                '%position%' => $position,
                '%by%' => $securedBy,
            ]
        );
    }

    protected function getSecuredBy(Record $record, Record $oldRecord)
    {
        $securedBy = $this->timeFormater->timeToText($oldRecord->getScore() - $record->getScore(), true);

        if (substr($securedBy, 0, 4) === "00:0") {
            $securedBy = substr($securedBy, 4);
        } else {
            if (substr($securedBy, 0, 3) === "00:") {
                $securedBy = substr($securedBy, 3);
            }
        }

        return '-'.$securedBy;
    }

    protected function messageFirstPlaceNew(Record $record)
    {
        $this->sendMessage(
            'new.top1',
            null,
            [
                '%nickname%' => $this->playerStorage->getPlayerInfo($record->getPlayerLogin())->getNickName(),
                '%score%' => $this->timeFormater->timeToText($record->getScore(), true),
                '%position%' => 1,
            ]
        );
    }

    protected function sendMessage($message, $recipe, $params)
    {
        $this->chatNotification->sendMessage(
            $this->translationPrefix.'.'.$message,
            $recipe,
            $params
        );
    }
}
