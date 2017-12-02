<?php

namespace Tests\eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\GameManiaplanet\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Core\Exceptions\PlayerException;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\ChatOutput;
use eXpansion\Framework\Core\Model\ChatCommand\ChatCommandPlugin;
use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Framework\Core\Services\ChatCommands;
use Symfony\Component\Console\Output\NullOutput;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestChatCommand;

class ChatCommandProviderTest extends TestCore
{
    /** @var  \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatCommands;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockNotification;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatOutput;

    /** @var  ChatCommandDataProvider */
    protected $chatDataProvider;

    protected function setUp()
    {
        parent::setUp();

        $this->mockChatCommands = $this->getMockBuilder(ChatCommands::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockNotification = $this->getMockBuilder(ChatNotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockChatOutput = $this->getMockBuilder(ChatOutput::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->chatDataProvider = new ChatCommandDataProvider(
            $this->mockChatCommands,
            $this->mockNotification,
            $this->mockChatOutput
        );
    }

    /**
     * Test that a registered plugin will be sent properly to the chat commands main service.
     */
    public function testRegister()
    {
        $commands = new TestChatCommand('test', [], true);
        /** @var ChatCommandPlugin|object $plugin */
        $plugin = new ChatCommandPlugin([$commands]);

        $this->mockChatCommands->expects($this->once())->method('registerPlugin')->with('test', $plugin);

        $this->chatDataProvider->registerPlugin('test', $plugin);
    }

    /**
     * Test that a deleted plugin will be sent propelry to the chat commands main service.
     */
    public function testDelete()
    {
        $this->mockChatCommands->expects($this->once())->method('deletePlugin')->with('test');
        $this->chatDataProvider->deletePlugin('test');
    }

    /**
     * Test that a normal chat line doesen't execute a command and that simple commands work.
     */
    public function testExecute()
    {
        $cmdText = 'value1 "value2 space"';

        $commands = $this->createMock(TestChatCommand::class);
        $commands->expects($this->once())
            ->method('validate')
            ->with('test', $cmdText)
            ->willReturn([]);
        $commands->expects($this->once())->method('run');

        $this->mockChatCommands
            ->expects($this->once())
            ->method('getChatCommand')
            ->willReturn([$commands, explode(' ', $cmdText)]);

        $this->chatDataProvider->onPlayerChat(2, 'test2', "this is normal chat line", false);
        $this->chatDataProvider->onPlayerChat(1, 'test', "/test $cmdText", true);
    }

    /**
     * Test players just chatting around.
     */
    public function testChat()
    {
        $cmdText = 'value1 value2';

        $this->mockChatCommands
            ->expects($this->never())
            ->method('getChatCommand');

        $this->chatDataProvider->onPlayerChat(2, 'test2', "this is normal chat line", false);
        $this->chatDataProvider->onPlayerChat(1, 'test', "test $cmdText", false);
    }

    /**
     * Test that an invalid command will show in player chat proper message.
     */
    public function testInvalidCommand()
    {
        $cmdText = 'value1 value2';

        $this->mockNotification
            ->expects($this->once())
            ->method('sendMessage')
            ->with('expansion_core.chat_commands.wrong_chat', 'test');

        $this->mockChatCommands
            ->expects($this->once())
            ->method('getChatCommand')
            ->willReturn(array(null, null));

        $this->chatDataProvider->onPlayerChat(1, 'test', "/invalid $cmdText", true);
    }

    /**
     * Test that the native dedicated version command doesen't trigger a chat command.
     */
    public function testVersionCommand()
    {
        $cmdText = 'value1';

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $this->mockChatCommands
            ->expects($this->never())
            ->method('getChatCommand');

        $this->chatDataProvider->onPlayerChat('test', 'test', "/version $cmdText", true);
    }

    /**
     * Test that an unexpected exception during chat execution should be thrown and not cached.
     */
    public function testUnExpectedException()
    {
        $cmdText = 'value1 "value2 space"';

        $commands = $this->createMock(TestChatCommand::class);
        $commands->expects($this->once())
            ->method('validate')
            ->with('test', $cmdText)
            ->willReturn([]);
        $commands->expects($this->once())->method('run')->willThrowException(new \Exception('Un expected'));

        $this->mockChatCommands
            ->expects($this->once())
            ->method('getChatCommand')
            ->willReturn([$commands, explode(' ', $cmdText)]);

        $this->expectException(\Exception::class);
        $this->chatDataProvider->onPlayerChat(1, 'test', "/test $cmdText", true);
    }


    /**
     * Test that an a player exception is catched and that message is sent accordingly.
     */
    public function testPlayerException()
    {
        $cmdText = 'value1 "value2 space"';

        $commands = $this->createMock(TestChatCommand::class);
        $commands->expects($this->once())
            ->method('validate')
            ->with('test', $cmdText)
            ->willReturn([]);
        $commands
            ->expects($this->once())
            ->method('run')
            ->willThrowException(new PlayerException('my message'));

        $this->mockChatCommands
            ->expects($this->once())
            ->method('getChatCommand')
            ->willReturn([$commands, explode(' ', $cmdText)]);

        $this->mockNotification->expects($this->once())->method('sendMessage')->with('my message');

        $this->chatDataProvider->onPlayerChat(1, 'test', "/test $cmdText", true);
    }
}
