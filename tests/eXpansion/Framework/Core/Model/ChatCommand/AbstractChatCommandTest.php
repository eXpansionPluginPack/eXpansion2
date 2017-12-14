<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 17:41
 */

namespace Tests\eXpansion\Framework\Core\Model\ChatCommand;

use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Helpers\ChatOutput;
use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use PHPUnit\Framework\TestCase;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestChatCommand;

class AbstractChatCommandTest extends TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatOutputHelper;

    protected function setUp()
    {
        parent::setUp();

        $this->mockChatNotification = $this->getMockBuilder(ChatNotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockChatOutputHelper = $this->getMockBuilder(ChatOutput::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockChatOutputHelper->method('getChatNotification')->willReturn($this->mockChatNotification);
    }

    public function testModel()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->assertEquals('test', $cmd2->getCommand());
        $this->assertEquals(['t'], $cmd2->getAliases());
        $this->assertEquals('expansion_core.chat_commands.no_description', $cmd2->getDescription());
        $this->assertEquals('expansion_core.chat_commands.no_help', $cmd2->getHelp());

        $cmd2->run('toto', $this->mockChatOutputHelper, '--help');
    }

    public function testHelp()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->getChatNotificationMock()
            ->expects($this->at(0))
            ->method('sendMessage')->with($cmd2->getDescription(), 'toto');

        $cmd2->run('toto', $this->mockChatOutputHelper, '--help');
        $this->assertFalse($cmd2->executed);
    }

    public function testExecute()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $cmd2->run('toto', $this->mockChatOutputHelper, '');
        $this->assertTrue($cmd2->executed);
    }

    public function testExecuteError()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->expectException(PlayerException::class);
        $cmd2->run('toto', $this->mockChatOutputHelper, 'test');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getChatNotificationMock()
    {
        return $this->mockChatNotification;
    }
}
