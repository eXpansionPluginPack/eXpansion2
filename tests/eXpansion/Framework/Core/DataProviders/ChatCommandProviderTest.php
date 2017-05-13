<?php

namespace Tests\eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Core\Model\ChatCommand\ChatCommandPlugin;
use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Framework\Core\Services\ChatCommands;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestChatCommand;

class ChatCommandProviderTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $chatCommandsMock = $this->getMockBuilder(ChatCommands::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('expansion.framework.core.services.chat_commands', $chatCommandsMock);

        $notification = $this->getMockBuilder(ChatNotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('expansion.framework.core.helpers.chat_notification', $notification);
    }

    public function testRegister()
    {
        $commands = new TestChatCommand('test', [], true);
        $plugin = new ChatCommandPlugin([$commands]);

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.framework.core.services.chat_commands');
        $chatCommandsMock->expects($this->once())->method('registerPlugin')->with('test', $plugin);

        $this->getDataProvider()->registerPlugin('test', $plugin);
    }


    public function testDelete()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.framework.core.services.chat_commands');
        $chatCommandsMock->expects($this->once())->method('deletePlugin')->with('test');

        $this->getDataProvider()->deletePlugin('test');
    }

    public function testExecute()
    {
        $cmdText = 'value1 value2';

        $commands = $this->createMock(TestChatCommand::class);
        $commands->expects($this->once())
            ->method('validate')
            ->with('test', $cmdText)
            ->willReturn([]);
        $commands->expects($this->once())->method('run');

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.framework.core.services.chat_commands');
        $chatCommandsMock
            ->expects($this->once())
            ->method('getChatCommand')
            ->willReturn([$commands, explode(' ', $cmdText)]);

        $this->getDataProvider()->onPlayerChat('test', 'test', "/test $cmdText", true);
    }

    public function testChat()
    {
        $cmdText = 'value1 value2';

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.framework.core.services.chat_commands');
        $chatCommandsMock
            ->expects($this->never())
            ->method('getChatCommand');

        $this->getDataProvider()->onPlayerChat('test', 'test', "test $cmdText", false);
    }

    public function testInvalidCommand()
    {
        $cmdText = 'value1 value2';


        $this->getChatNotificationMock()
            ->expects($this->once())
            ->method('sendMessage')
            ->with('expansion_core.chat_commands.wrong_chat', 'test');

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.framework.core.services.chat_commands');
        $chatCommandsMock
            ->expects($this->once())
            ->method('getChatCommand')
            ->willReturn(array(null, null));

        $this->getDataProvider()->onPlayerChat('test', 'test', "/test $cmdText", true);
    }



    public function testVersionCommand()
    {
        $cmdText = 'value1 value2';

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.framework.core.services.chat_commands');
        $chatCommandsMock
            ->expects($this->never())
            ->method('getChatCommand');

        $this->getDataProvider()->onPlayerChat('test', 'test', "/version $cmdText", true);
    }


    /**
     * @return  ChatCommandDataProvider
     */
    protected function getDataProvider()
    {
        return $this->container->get('expansion.framework.core.data_providers.chat_command_data_provider');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getChatNotificationMock()
    {
        return $this->container->get('expansion.framework.core.helpers.chat_notification');
    }
}
