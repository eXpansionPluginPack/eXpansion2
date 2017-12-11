<?php

namespace Tests\eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Bundle\LocalRecords\Plugins\RaceRecords;
use eXpansion\Bundle\LocalRecords\Services\RecordHandler;
use eXpansion\Bundle\LocalRecords\Services\RecordHandlerFactory;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\GameTrackmania\ScriptMethods\GetNumberOfLaps;
use Maniaplanet\DedicatedServer\Structures\Map;

class RaceRecordsTest extends \PHPUnit_Framework_TestCase
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
    protected $dispatcher;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockGetNbLaps;

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

    public function testActivation()
    {
        $this->mapStorageMock->expects($this->once())->method('getCurrentMap')->willReturn(new Map());
        $this->recordHanlderMock->expects($this->once())->method('loadForMap');
        $this->recordHanlderMock->expects($this->once())->method('loadForPlayers');
        $this->mockGetNbLaps
            ->expects($this->once())
            ->method('get')
            ->willReturnCallback(function ($call) {
                $call(1);
            });

        $this->getRaceRecords()->setStatus(true);
    }

    public function testMapChange()
    {
        $this->recordHanlderMock->expects($this->once())->method('loadForMap');
        $this->recordHanlderMock->expects($this->once())->method('loadForPlayers');
        $this->mockGetNbLaps
            ->expects($this->once())
            ->method('get')
            ->willReturnCallback(function ($call) {
                $call(1);
            });

        $this->getRaceRecords()->onStartMapStart(0,0,false, new Map());
    }

    public function testPlayerConnect()
    {
        $this->mapStorageMock->expects($this->once())->method('getCurrentMap')->willReturn(new Map());
        $this->recordHanlderMock->expects($this->once())->method('loadForPlayers');

        $this->getRaceRecords()->onPlayerConnect(new Player());
    }

    public function testPlayerEndRace()
    {
        $this->recordHanlderMock->expects($this->once())->method('addRecord')->willReturn(null);
        $this->dispatcher->expects($this->never())->method('dispatch');

        $this->getRaceRecords()->onPlayerEndRace(
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

    public function testDispatchEvent()
    {
        $this->recordHanlderMock->expects($this->once())->method('addRecord')->willReturn(['event' => 'toto']);
        $this->dispatcher->expects($this->exactly(1))->method('dispatch');

        $this->getRaceRecords()->onPlayerEndRace(
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

    public function testUnusedCallbacks()
    {
        $raceRecords = $this->getRaceRecords();

        $raceRecords->setStatus(false);
        $raceRecords->onPlayerInfoChanged(new Player(), new Player());
        $raceRecords->onPlayerAlliesChanged(new Player(), new Player());
        $raceRecords-> onPlayerDisconnect(new Player(), '');
        $raceRecords->onEndMapEnd(0, 0, false, new Map());
        $raceRecords->onStartMapEnd(0, 0, false, new Map());
        $raceRecords->onEndMapStart(0, 0, false, new Map());
        $raceRecords->onStartMatchStart(0, 0);
        $raceRecords->onStartMatchEnd(0, 0);
        $raceRecords->onPlayerEndLap(0, 0, 0, 0, 0, 0, 0, 0, 0);
        $raceRecords->onPlayerWayPoint(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0);
        $raceRecords->onEndMatchStart(0, 0);
        $raceRecords->onEndMatchEnd(0, 0);
        $raceRecords->onStartTurnStart(0, 0);
        $raceRecords->onStartTurnEnd(0, 0);
        $raceRecords->onEndTurnStart(0, 0);
        $raceRecords->onEndTurnEnd(0, 0);
        $raceRecords->onStartRoundStart(0, 0);
        $raceRecords->onStartRoundEnd(0, 0);
        $raceRecords->onEndRoundStart(0, 0);
        $raceRecords->onEndRoundEnd(0, 0);
        $raceRecords->getRecordsHandler();


    }


    /**
     * @return RaceRecords
     */
    protected function getRaceRecords()
    {
        return new RaceRecords(
            $this->recordHanlderFactoryMock,
            $this->playersGroup,
            $this->mapStorageMock,
            $this->dispatcher,
            $this->mockGetNbLaps,
            'prefix'
        );
    }
}
