<?php
/**
 * File ReasonUserCommandTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
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
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class OneParameterCommandTest extends TestCore
{
    use PlayerDataTrait;

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
        parent::setUp();

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
            'setServerName',
            'parameter description',
            $this->getMockBuilder(AdminGroups::class)->disableOriginalConstructor()->getMock(),
            $this->mockConnectionFactory,
            $this->chatNotificationMock,
            $this->playerStorageMock,
            $this->getMockBuilder(LoggerInterface::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(Time::class)->disableOriginalConstructor()->getMock()
        );
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

        $this->mockConnection->expects($this->once())->method('setServerName')->with('testname');

        $this->chatNotificationMock->expects($this->once())->method('sendMessage')->with(
            'expansion_admin_chat.setservername.msg', null,
            ['%adminLevel%' => 'Admin', '%admin%' => '$ffftest', '%parameter%' => 'testname']
        );

        $this->oneParameterCommand->execute(
            'test',
            $this->inputMock
        );
    }
}
