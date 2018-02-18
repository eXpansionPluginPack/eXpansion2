<?php

namespace Tests\eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Plugins\ChatNotification;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Model\IntegerConfig;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\PlayersBundle\Model\Player;
use eXpansion\Framework\PlayersBundle\Storage\PlayerDb;

class ChatNotificationTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $chatNotificationHelper;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $playerDbStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $playerStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $timeFormatter;

    protected function setUp()
    {
        parent::setUp();

        $this->chatNotificationHelper = $this->getMockBuilder(\eXpansion\Framework\Core\Helpers\ChatNotification::class)
            ->disableOriginalConstructor()->getMock();

        $this->playerDbStorage = $this->getMockBuilder(PlayerDb::class)
            ->disableOriginalConstructor()->getMock();
        $this->playerDbStorage->method('get')->willReturnCallback(function($login) {
            $player = new Player();
            $player->setLogin($login);
            $player->setNickname($login . ' - ' . 'nick');

            return $player;
        });

        $this->playerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()->getMock();

        $this->timeFormatter = $this->getMockBuilder(Time::class)->getMock();
    }

    /**
     *
     */
    public function testLoad()
    {
        $this->playerStorage->expects($this->once())->method('getOnline')->willReturn(
            [
                'toto-1' => new Player(),
                'toto-2' => new Player(),
                'toto-4' => new Player(),
            ]
        );

        $this->chatNotificationHelper
            ->expects($this->at(0))
            ->method('sendMessage')
            ->with(
                'prefix.loaded.top1',
                null,
                ['%nickname%' => 'toto-1 - nick', '%score%' => null]
            );
        $this->chatNotificationHelper
            ->expects($this->at(1))
            ->method('sendMessage')
            ->with(
                'prefix.loaded.any',
                'toto-2',
                ['%nickname%' => 'toto-2 - nick', '%score%' => null, '%position%' => 2]
            );
        $this->chatNotificationHelper
            ->expects($this->at(2))
            ->method('sendMessage')
            ->with(
                'prefix.loaded.any',
                'toto-4',
                ['%nickname%' => 'toto-4 - nick', '%score%' => null, '%position%' => 4]
            );

        $cnotificaiton = $this->getChatNotification();
        $records = [
            $this->getRecord('toto-1', 10),
            $this->getRecord('toto-2', 20),
            $this->getRecord('toto-3', 30),
            $this->getRecord('toto-4', 40),
        ];

        $cnotificaiton->onLocalRecordsLoaded($records);
    }

    public function testFirstRecord() {
        $cnotificaiton = $this->getChatNotification();

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.new.top1',
                null,
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 1]
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 1]
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 1, '%old_position%' => 2, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 4, '%old_position%' => 7, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 8, '%old_position%' => 10, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 8, '%old_position%' => null, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 12, '%old_position%' => 30, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 1, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 5, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 8, '%by%' => '-']
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
                ['%nickname%' => 'toto - nick', '%score%' => null, '%position%' => 30, '%by%' => '-']
            );

        $cnotificaiton->onLocalRecordsSamePosition(
            $this->getRecord('toto', 10),
            $this->getRecord('toto', 8),
            [],
            30
        );
    }

    public function testBy()
    {
        $cnotificaiton = $this->getChatNotification();
        $this->timeFormatter->method('timeToText')->willReturn('00:10.00');

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.any',
                'toto',
                ['%nickname%' => 'toto - nick', '%score%' => '00:10.00', '%position%' => 30, '%by%' => '-10.00']
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
        $this->timeFormatter->method('timeToText')->willReturn('00:01.00');

        $this->chatNotificationHelper
            ->expects($this->once())
            ->method('sendMessage')
            ->with(
                'prefix.secures.any',
                'toto',
                ['%nickname%' => 'toto - nick', '%score%' => '00:01.00', '%position%' => 30, '%by%' => '-1.00']
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
        $config = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $config->method('get')->willReturn(10);

        return new ChatNotification(
            $this->chatNotificationHelper,
            $this->timeFormatter,
            $this->playerStorage,
            'prefix',
            $config
        );
    }

    protected function getRecord($login, $score)
    {
        $record = new Record();
        $record->setPlayer($this->playerDbStorage->get($login));
        $record->setScore($score);

        return $record;
    }

}
