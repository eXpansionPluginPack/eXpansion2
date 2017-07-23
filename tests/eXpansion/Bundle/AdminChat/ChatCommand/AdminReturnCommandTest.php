<?php
/**
 * File ReasonUserCommandTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Bundle\AdminChat\ChatCommand\AdminCommand;
use eXpansion\Bundle\AdminChat\ChatCommand\AdminReturnCommand;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class AdminReturnCommandTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $connectionMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $chatNotificationMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $playerStorageMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $inputMock;

    /** @var AdminReturnCommand */
    protected $adminCommand;

    /**
     *
     */
    protected function setUp()
    {
        $this->connectionMock = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->chatNotificationMock = $this->getMockBuilder(ChatNotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->playerStorageMock = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->inputMock = $this->getMockBuilder(InputInterface::class)->disableOriginalConstructor()->getMock();

        $this->adminCommand = new AdminReturnCommand(
            'toto',
            'planets',
            [],
            $this->getMockBuilder(AdminGroups::class)->disableOriginalConstructor()->getMock(),
            $this->connectionMock,
            $this->chatNotificationMock,
            $this->playerStorageMock,
            $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(Time::class)->disableOriginalConstructor()->getMock()
        );

        $this->adminCommand->setDescription('description');
        $this->adminCommand->setChatMessage('server has %planets% planets');
        $this->adminCommand->setFunctionName('getServerPlanets');
    }

    public function setFunctionName()
    {
        $this->assertEquals('getServerPlanets', $this->adminCommand->getFunctionName());
    }

    public function setChatMessage()
    {
        $this->assertEquals('message', $this->adminCommand->getChatMessage());
    }

    public function testDescription()
    {
        $this->assertEquals('description', $this->adminCommand->getDescription());
    }

    public function testSetPublic()
    {
        $this->adminCommand->setPublic(true);
        $this->assertTrue($this->adminCommand->getPublic());

        $this->adminCommand->setPublic(false);
        $this->assertFalse($this->adminCommand->getPublic());
    }

    /**
     *
     */
    public function testExectutePublic()
    {

        $this->playerStorageMock
            ->method("getPlayerInfo")
            ->with('test')
            ->willReturn(
                $this->getPlayer(
                    'test',
                    false
                )
            );

        $this->connectionMock->expects($this->once())->method('getServerPlanets');

        $this->chatNotificationMock->expects($this->once())->method('sendMessage')
            ->with('server has %planets% planets', null, ['%adminLevel%' => 'Admin', '%admin%' => '$ffftest', '%return%' => 0]);

        $this->adminCommand->execute(
            'test',
            $this->inputMock
        );
    }    public function testExectutePrivate()
    {

        $this->playerStorageMock
            ->method("getPlayerInfo")
            ->with('test')
            ->willReturn(
                $this->getPlayer(
                    'test',
                    false
                )
            );

        $this->connectionMock->expects($this->once())->method('getServerPlanets');
        // test personal message

        $this->adminCommand->setPublic(false);

        $this->connectionMock->expects($this->once())->method('getServerPlanets')->with();
        $this->chatNotificationMock->expects($this->once())->method('sendMessage')
            ->with('server has %planets% planets', 'test', ['%adminLevel%' => 'Admin', '%admin%' => '$ffftest', '%return%' => 0]);


        $this->adminCommand->execute(
            'test',
            $this->inputMock
        );
    }
}
