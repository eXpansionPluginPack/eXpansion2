<?php

namespace Tests\eXpansion\Bundle\LocalRecords\Services;

use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Bundle\LocalRecords\Model\RecordQuery;
use eXpansion\Bundle\LocalRecords\Model\RecordQueryBuilder;
use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\PlayersBundle\Model\Player;
use eXpansion\Framework\PlayersBundle\Storage\PlayerDb;

class RecordHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockRecordQueryBuilder;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $playerDbMock;

    protected function setUp()
    {
        parent::setUp();

        $this->mockRecordQueryBuilder = $this->getMockBuilder(RecordQueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->playerDbMock = $this->getMockBuilder(PlayerDb::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->playerDbMock->method('get')->willReturnCallback(function($login) {
            $player = new Player();
            $player->setLogin($login);
            $player->setNickname($login . ' - ' . 'nick');

            return $player;
        });
    }

    /**
     *
     */
    public function testLoad()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];
        $records2 = [
            $this->createRecord('toto4', 40),
            $this->createRecord('toto5', 50),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);
        $this->mockRecordQueryBuilder
            ->expects($this->at(1))
            ->method('getPlayerMapRecords')
            ->willReturn($records2);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $this->assertEquals($records, $recordHandler->getRecords());
        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(2, $recordHandler->getPlayerPosition('toto2'));
        $this->assertEquals(3, $recordHandler->getPlayerPosition('toto3'));
        $this->assertEquals(10, $recordHandler->getPlayerRecord('toto1')->getScore());
        $this->assertEquals(20, $recordHandler->getPlayerRecord('toto2')->getScore());
        $this->assertEquals(30, $recordHandler->getPlayerRecord('toto3')->getScore());
        $this->assertEquals(null, $recordHandler->getPlayerRecord('toto4'));

        $recordHandler->loadForPlayers('', 1, ['toto4', 'toto5']);

        $this->assertEquals($records, $recordHandler->getRecords());
        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(2, $recordHandler->getPlayerPosition('toto2'));
        $this->assertEquals(3, $recordHandler->getPlayerPosition('toto3'));
        $this->assertEquals(null, $recordHandler->getPlayerPosition('toto4'));
        $this->assertEquals(null, $recordHandler->getPlayerPosition('toto5'));
        $this->assertEquals(10, $recordHandler->getPlayerRecord('toto1')->getScore());
        $this->assertEquals(20, $recordHandler->getPlayerRecord('toto2')->getScore());
        $this->assertEquals(30, $recordHandler->getPlayerRecord('toto3')->getScore());
        $this->assertEquals(40, $recordHandler->getPlayerRecord('toto4')->getScore());
        $this->assertEquals(50, $recordHandler->getPlayerRecord('toto5')->getScore());

        $this->assertEquals(null, $recordHandler->getPlayerRecord('toto6'));
    }


    public function testFirstRecord()
    {
        $recordHandler = $this->getRecordHandler(3);

        $eventData = $recordHandler->addRecord('toto1', 20, [10,20]);

        $this->assertEquals(RecordHandler::EVENT_TYPE_FIRST_TIME, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);

        $this->assertCount(1, $recordHandler->getRecords());
        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(20, $recordHandler->getPlayerRecord('toto1')->getScore());

        // Same player improves time.
        $eventData = $recordHandler->addRecord('toto1', 10, [5,10]);

        $this->assertEquals(RecordHandler::EVENT_TYPE_SAME_POS, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);

        $this->assertCount(1, $recordHandler->getRecords());
        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(10, $recordHandler->getPlayerRecord('toto1')->getScore());
        $this->assertEquals(15, $recordHandler->getPlayerRecord('toto1')->getAvgScore());
        $this->assertEquals(2, $recordHandler->getPlayerRecord('toto1')->getNbFinish());
    }

    public function testImproveRecord()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto2', 8, [5,8]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_BETTER_POS, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);
        $this->assertEquals(2, $eventData[RecordHandler::COL_OLD_POS]);
        $this->assertEquals(8, $eventData[RecordHandler::COL_RECORD]->getScore());
        $this->assertEquals(20, $eventData[RecordHandler::COL_OLD_RECORD]->getScore());

        $records =  $recordHandler->getRecords();
        $this->assertCount(3,$records);
        $this->assertEquals('toto2 - nick', $records[0]->getPlayer()->getNickname());
        $this->assertEquals('toto1 - nick', $records[1]->getPlayer()->getNickname());
        $this->assertEquals('toto3 - nick', $records[2]->getPlayer()->getNickname());
        $this->assertEquals(2, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto2'));

        $eventData = $recordHandler->addRecord('toto3', 9, [5,9]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_BETTER_POS, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(2, $eventData[RecordHandler::COL_POS]);
        $this->assertEquals(3, $eventData[RecordHandler::COL_OLD_POS]);

        $this->assertCount(3, $recordHandler->getRecords());
        $this->assertEquals(3, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto2'));
        $this->assertEquals(2, $recordHandler->getPlayerPosition('toto3'));
    }

    public function testImproveRecordMore()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto3', 9, [5,9]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_BETTER_POS, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);
        $this->assertEquals(3, $eventData[RecordHandler::COL_OLD_POS]);

        $records =  $recordHandler->getRecords();
        $this->assertCount(3,$records);
        $this->assertEquals('toto3 - nick', $records[0]->getPlayer()->getNickname());
        $this->assertEquals('toto1 - nick', $records[1]->getPlayer()->getNickname());
        $this->assertEquals('toto2 - nick', $records[2]->getPlayer()->getNickname());

        $this->assertEquals(2, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(3, $recordHandler->getPlayerPosition('toto2'));
        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto3'));
    }

    public function testImproveAndNot()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto1', 8, [5,8]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_SAME_POS, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);
        $records =  $recordHandler->getRecords();
        $this->assertEquals('8', $records[0]->getScore());

        $eventData = $recordHandler->addRecord('toto1', 9, [5,9]);
        $this->assertNull($eventData);
        $records =  $recordHandler->getRecords();
        $this->assertEquals('8', $records[0]->getScore());
    }

    public function testFirstImproveAndNot()
    {
        $records = [ ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto1', 8, [5,8]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_FIRST_TIME, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);
        $records =  $recordHandler->getRecords();
        $this->assertEquals('8', $records[0]->getScore());

        $eventData = $recordHandler->addRecord('toto1', 9, [5,9]);
        $this->assertNull($eventData);
        $records =  $recordHandler->getRecords();
        $this->assertEquals('8', $records[0]->getScore());
    }

    public function testNewRecord()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto4', 9, [5,9]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_BETTER_POS, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);
        $this->assertEquals(null, $eventData[RecordHandler::COL_OLD_POS]);

        $records =  $recordHandler->getRecords();
        $this->assertCount(3,$records);
        $this->assertEquals('toto4 - nick', $records[0]->getPlayer()->getNickname());
        $this->assertEquals('toto1 - nick', $records[1]->getPlayer()->getNickname());
        $this->assertEquals('toto2 - nick', $records[2]->getPlayer()->getNickname());

        $this->assertEquals(1, $recordHandler->getPlayerPosition('toto4'));
        $this->assertEquals(2, $recordHandler->getPlayerPosition('toto1'));
        $this->assertEquals(3, $recordHandler->getPlayerPosition('toto2'));
    }

    public function testSameTime()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto3', 30, [5,9]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_SAME_SCORE, $eventData[RecordHandler::COL_EVENT]);
    }

    public function testWorseTime()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto3', 40, [5,9]);
        $this->assertNull($eventData);
    }

    public function testWorseNewTimeIngored()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto4', 40, [5,9]);
        $this->assertNull($eventData);
    }

    public function testWorseNewTime()
    {
        $records = [
            $this->createRecord('toto1', 10),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 30),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(5);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto4', 40, [5,9]);
        $this->assertCount(4, $eventData['records']);
    }

    public function testForScores()
    {
        $records = [
            $this->createRecord('toto1', 30),
            $this->createRecord('toto2', 20),
            $this->createRecord('toto3', 10),
        ];

        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(3, RecordHandler::ORDER_DESC);
        $recordHandler->loadForMap('', 1);

        $eventData = $recordHandler->addRecord('toto3', 40, [5,9]);
        $this->assertEquals(RecordHandler::EVENT_TYPE_BETTER_POS, $eventData[RecordHandler::COL_EVENT]);
        $this->assertEquals(1, $eventData[RecordHandler::COL_POS]);
        $this->assertEquals(3, $eventData[RecordHandler::COL_OLD_POS]);
    }

    public function testInsertNew()
    {
        $records = [
            $this->createRecord('toto1', 10),
        ];
        $this->mockRecordQueryBuilder
            ->expects($this->at(0))
            ->method('getMapRecords')
            ->willReturn($records);

        $recordHandler = $this->getRecordHandler(10, RecordHandler::ORDER_ASC);
        $recordHandler->loadForMap('', 1);

        $recordHandler->addRecord('toto4', 8, [5,15]);
        $this->assertNull($eventData = $recordHandler->addRecord('toto4', 12, [5,15]));
    }

    /**
     * @return RecordHandler
     */
    protected function getRecordHandler($limit = 10, $order = RecordHandler::ORDER_ASC)
    {
        $config = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $config->method('get')->willReturn($limit);

        return new RecordHandler($this->mockRecordQueryBuilder, $this->playerDbMock, $config, $order);
    }

    /**
     * @param $login
     * @param $score
     *
     * @return Record
     */
    protected function createRecord($login, $score)
    {
        $player = new Player();
        $player->setLogin($login);
        $player->setNickname($login . ' - nick');

        $record = new Record();
        $record->setPlayer($player);
        $record->setScore($score);

        return $record;
    }

}
