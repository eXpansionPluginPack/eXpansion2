<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 17:41
 */

namespace Tests\eXpansion\Framework\Core\Model\ChatCommand;

use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\ChatOutput;
use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestChatCommand;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestMultiParameterChatCommand;

class AbstractChatCommandTest extends TestCore
{
    protected $mockChatNotification;

    protected $chatOutput;

    protected function setUp()
    {
        parent::setUp();

        $this->mockChatNotification = $this->getMockBuilder(ChatNotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->chatOutput = new ChatOutput($this->mockConnectionFactory, $this->mockChatNotification);
    }

    public function testModel()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->assertEquals('test', $cmd2->getCommand());
        $this->assertEquals(['t'], $cmd2->getAliases());
        $this->assertEquals('expansion_core.chat_commands.no_description', $cmd2->getDescription());
        $this->assertEquals('expansion_core.chat_commands.no_help', $cmd2->getHelp());

        $cmd2->run('toto', $this->chatOutput, '--help');
    }

    public function testHelp()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->mockChatNotification
            ->expects($this->at(0))
            ->method('sendMessage')->with($cmd2->getDescription(), 'toto');

        $cmd2->run('toto', $this->chatOutput, '--help');
        $this->assertFalse($cmd2->executed);
    }

    public function testExecute()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $cmd2->run('toto', $this->chatOutput, '');
        $this->assertTrue($cmd2->executed);
    }

    public function testExecuteMultiParameter()
    {
        $cmd2 = new TestMultiParameterChatCommand('test', ['t']);
        $cmd2->run('toto', $this->chatOutput, 'toto "reason here"');

        $this->assertEquals('toto', $cmd2->input->getArgument('login'));
        $this->assertEquals('reason here', $cmd2->input->getArgument('reason'));
    }

    public function testExecuteError()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->expectException(PlayerException::class);
        $cmd2->run('toto', $this->chatOutput, 'test');
    }
}
