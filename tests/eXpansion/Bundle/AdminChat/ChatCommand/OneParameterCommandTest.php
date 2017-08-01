<?php
/**
 * File ReasonUserCommandTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Bundle\AdminChat\ChatCommand\OneParameterCommand;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class OneParameterCommandTest extends \PHPUnit_Framework_TestCase
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

    /** @var OneParameterCommand */
    protected $oneParameterCommand;

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

        $this->oneParameterCommand = new OneParameterCommand(
            'toto',
            'toto',
            [],
            $this->getMockBuilder(AdminGroups::class)->disableOriginalConstructor()->getMock(),
            $this->connectionMock,
            $this->chatNotificationMock,
            $this->playerStorageMock,
            $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(Time::class)->disableOriginalConstructor()->getMock()
        );

        $this->oneParameterCommand->setParameterDescription('parameter description');
        $this->oneParameterCommand->setDescription('parameter description');
        $this->oneParameterCommand->setChatMessage('%adminLevel% %admin% sets server name %parameter%');
        $this->oneParameterCommand->setFunctionName('setServerName');
    }

    public function testDescription()
    {
        $this->assertEquals('parameter description', $this->oneParameterCommand->getDescription());
    }

    public function testExectute()
    {
        $this->inputMock
            ->expects($this->at(0))
            ->method('getArgument')
            ->with('parameter')
            ->willReturn('testname');

        $this->playerStorageMock
            ->method("getPlayerInfo")
            ->with('test')
            ->willReturn(
                $this->getPlayer(
                    'test',
                    false
                )
            );

        $this->connectionMock->expects($this->once())->method('setServerName')->with('testname');

        $this->chatNotificationMock->expects($this->once())->method('sendMessage')->with(
            '%adminLevel% %admin% sets server name %parameter%', null,
            ['%adminLevel%' => 'Admin', '%admin%' => '$ffftest', '%parameter%' => 'testname']
        );

        $this->oneParameterCommand->execute(
            'test',
            $this->inputMock
        );
    }
}
