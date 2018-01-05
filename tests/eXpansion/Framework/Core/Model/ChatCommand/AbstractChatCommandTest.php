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
use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestChatCommand;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestMultiParameterChatCommand;

class AbstractChatCommandTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $notification = $this->getMockBuilder(ChatNotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set(ChatNotification::class, $notification);
    }

    public function testModel()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->assertEquals('test', $cmd2->getCommand());
        $this->assertEquals(['t'], $cmd2->getAliases());
        $this->assertEquals('expansion_core.chat_commands.no_description', $cmd2->getDescription());
        $this->assertEquals('expansion_core.chat_commands.no_help', $cmd2->getHelp());

        $cmd2->run('toto', $this->getChatOutputHelper(), '--help');
    }

    public function testHelp()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->getChatNotificationMock()
            ->expects($this->at(0))
            ->method('sendMessage')->with($cmd2->getDescription(), 'toto');

        $cmd2->run('toto', $this->getChatOutputHelper(), '--help');
        $this->assertFalse($cmd2->executed);
    }

    public function testExecute()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $cmd2->run('toto', $this->getChatOutputHelper(), '');
        $this->assertTrue($cmd2->executed);
    }

    public function testExecuteMultiParameter()
    {
        $cmd2 = new TestMultiParameterChatCommand('test', ['t']);
        $cmd2->run('toto', $this->getChatOutputHelper(), 'toto "reason here"');

        $this->assertEquals('toto', $cmd2->input->getArgument('login'));
        $this->assertEquals('reason here', $cmd2->input->getArgument('reason'));
    }

    public function testExecuteError()
    {
        $cmd2 = new TestChatCommand('test', ['t']);

        $this->expectException(PlayerException::class);
        $cmd2->run('toto', $this->getChatOutputHelper(), 'test');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getChatNotificationMock()
    {
        return $this->container->get(ChatNotification::class);
    }
}
