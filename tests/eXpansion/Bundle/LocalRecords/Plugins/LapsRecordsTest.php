<?php

namespace Tests\eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Bundle\LocalRecords\Plugins\LapsRecords;
use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Bundle\LocalRecords\Services\RecordHandlerFactory;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameTrackmania\ScriptMethods\GetNumberOfLaps;

class LapsRecordsTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $recordHanlderMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $recordHanlderFactoryMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mapStorageMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $playersGroup;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockGetNbLaps;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $dispatcher;

    protected function setUp()
    {
        parent::setUp();

        $this->recordHanlderMock = $this->getMockBuilder(RecordHandler::class)->disableOriginalConstructor()
            ->getMock();

        $this->recordHanlderFactoryMock = $this->getMockBuilder(RecordHandlerFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->recordHanlderFactoryMock->method('create')->willReturn($this->recordHanlderMock);

        $this->mapStorageMock = $this->getMockBuilder(MapStorage::class)->getMock();

        $this->dispatcher = $this->getMockBuilder(Dispatcher::class)->disableOriginalConstructor()->getMock();

        $this->mockGetNbLaps = $this->getMockBuilder(GetNumberOfLaps::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->playersGroup = new Group(null, $this->dispatcher);
    }

    public function testPlayerEndLap()
    {
        $this->recordHanlderMock->expects($this->once())->method('addRecord')->willReturn(['event' => 'toto']);
        $this->dispatcher->expects($this->exactly(1))->method('dispatch');

        $this->getLapsRecords()->onPlayerEndLap(
            'toto1',
            1,
            10,
            10,
            10,
            [10],
            10,
            10,
            10
        );
    }

    public function testEmptyMethods()
    {
        $lapsRecords = $this->getLapsRecords();

        $lapsRecords->onPlayerEndRace(0, 0, 0, 0, 0, 0, 0, 0, 0);
        $lapsRecords->onPlayerWayPoint(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0);
    }


    /**
     * @return LapsRecords
     */
    protected function getLapsRecords()
    {
        return new LapsRecords(
            $this->recordHanlderFactoryMock,
            $this->playersGroup,
            $this->mapStorageMock,
            $this->dispatcher,
            $this->mockGetNbLaps,
            'prefix'
        );
    }
}
