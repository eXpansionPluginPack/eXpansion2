<?php
/**
 * File ReasonUserCommandTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\AdminChat\ChatCommand;

use eXpansion\Bundle\AdminChat\ChatCommand\ReasonUserCommand;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class ReasonUserCommandTest extends TestCore
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $chatNotificationMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $playerStorageMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $inputMock;

    /** @var ReasonUserCommand */
    protected $reasonCommand;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

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

        $this->reasonCommand = new ReasonUserCommand(
            'toto',
            'toto',
            [],
            'ban',
            'login description',
            'reason description',
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
            ->with('login')
            ->willReturn('test');
        $this->inputMock
            ->expects($this->at(1))
            ->method('getArgument')
            ->with('reason')
            ->willReturn('reason');

        $this->playerStorageMock
            ->method("getPlayerInfo")
            ->with('test')
            ->willReturn(
                $this->getPlayer(
                    'test',
                    false
                )
            );

        $this->mockConnection->expects($this->once())->method('ban')->with('test', 'reason');

        $this->chatNotificationMock->expects($this->once())->method('sendMessage')->with(
            'expansion_admin_chat.ban.msg', null,
            ['%adminLevel%' => 'Admin', '%admin%' => '$ffftest', '%player%' => '$ffftest', '%reason%' => 'reason']
        );

        $this->reasonCommand->execute(
            'test',
            $this->inputMock
        );
    }
}
