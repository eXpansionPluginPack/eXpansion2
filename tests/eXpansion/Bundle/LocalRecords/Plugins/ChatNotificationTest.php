<?php

namespace Tests\eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Bundle\LocalRecords\Entity\Record;
use eXpansion\Bundle\LocalRecords\Plugins\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;

class ChatNotificationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $chatNotificationHelper;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $playerStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $timeFormatter;

    protected function setUp()
    {
        parent::setUp();

        $this->chatNotificationHelper = $this->getMockBuilder(\eXpansion\Framework\Core\Helpers\ChatNotification::class)
            ->disableOriginalConstructor()->getMock();

        $this->playerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()->getMock();
        $this->playerStorage->method('getPlayerInfo')->willReturn(new Player());


        $this->timeFormatter = $this->getMockBuilder(Time::class)->getMock();
    }

    public function testFirstRecord() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.new.top1',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 1]
            );

        $cnotificaiton->onLocalRecordsFirstRecord($this->getRecord('toto', 10), [], 1);
    }

    public function testBestFirstRecord() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.new.top1',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 1]
            );

        $cnotificaiton->onLocalRecordsBetterPosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 10),
            [],
            1,
            null);
    }

    public function testBetterTop1() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.better.top1',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 1, '%old_position%' => 2, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsBetterPosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            1,
            2);
    }

    public function testBetterTop5() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.better.top5',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 4, '%old_position%' => 7, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsBetterPosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            4,
            7);
    }
    public function testBetterAny() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.better.any',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 8, '%old_position%' => 10, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsBetterPosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            8,
            10);
    }
    public function testBetterAnyNew() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.new.any',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 8, '%old_position%' => null, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsBetterPosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            8,
            null);
    }

    public function testBetterAnyBad() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.better.any',
                'toto',
                ['%nickname%' => null, '%score%' => null, '%position%' => 12, '%old_position%' => 30, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsBetterPosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            12,
            30);
    }

    public function testSamePositionFirst()
    {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.top1',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 1, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsSamePosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            1);
    }

    public function testSamePositionTop5()
    {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.top5',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 5, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsSamePosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            5);
    }


    public function testSamePositionAny()
    {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.any',
                null,
                ['%nickname%' => null, '%score%' => null, '%position%' => 8, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsSamePosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            8);
    }


    public function testSamePositionAnyNew()
    {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.any',
                'toto',
                ['%nickname%' => null, '%score%' => null, '%position%' => 30, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsSamePosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            30);
    }

    public function testBy()
    {
        $cnotificaiton = $this->getChatNotification();
        $this->timeFormatter->method('milisecondsToTrackmania')->willReturn('00:10:00');

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.any',
                'toto',
                ['%nickname%' => null, '%score%' => '00:10:00', '%position%' => 30, '%by%' => '-10:00']
            );

        $cnotificaiton->onLocalRecordsSamePosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            30);
    }

    public function testByShort()
    {
        $cnotificaiton = $this->getChatNotification();
        $this->timeFormatter->method('milisecondsToTrackmania')->willReturn('00:01:00');

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.any',
                'toto',
                ['%nickname%' => null, '%score%' => '00:01:00', '%position%' => 30, '%by%' => '-1:00']
            );

        $cnotificaiton->onLocalRecordsSamePosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            30);
    }

    public function testEmptyMethods()
    {
        $cnotificaiton = $this->getChatNotification();

        $cnotificaiton->onLocalRecordsLoaded([]);
        $cnotificaiton->onLocalRecordsSameScore($this->getRecord('toto', 10), $this->getRecord('toto', 8), []);
    }

    /**
     * @return ChatNotification
     */
    protected function getChatNotification()
    {
        return new ChatNotification(
            $this->chatNotificationHelper,
            $this->playerStorage,
            $this->timeFormatter,
            'prefix',
            10
        );
    }

    protected function getRecord($login, $score)
    {
        $record = new Record();
        $record->setPlayerLogin($login);
        $record->setScore($score);

        return $record;
    }

}
