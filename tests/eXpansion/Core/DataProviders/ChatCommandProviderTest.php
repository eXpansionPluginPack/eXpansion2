<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 01/04/2017
 * Time: 17:45
 */

namespace Tests\eXpansion\Core\DataProviders;

use eXpansion\Core\DataProviders\ChatCommandDataProvider;
use eXpansion\Core\Model\ChatCommand\ChatCommandPlugin;
use eXpansion\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Core\Services\ChatCommands;
use Tests\eXpansion\Core\TestCore;
use Tests\eXpansion\Core\TestHelpers\Model\TestChatCommand;

class ChatCommandProviderTest extends TestCore
{
    protected function setUp()
    {
        parent::setUp();

        $chatCommandsMock = $this->getMockBuilder(ChatCommands::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('expansion.core.services.chat_commands', $chatCommandsMock);

        $notification = $this->getMockBuilder(ChatNotificationInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->container->set('expansion.core.helpers.chat_notification', $notification);
    }

    public function testRegister()
    {
        $commands = new TestChatCommand('test', [], true);
        $plugin = new ChatCommandPlugin([$commands]);

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.core.services.chat_commands');
        $chatCommandsMock->expects($this->once())->method('registerPlugin')->with('test', $plugin);

        $this->getDataProvider()->registerPlugin('test', $plugin);
    }


    public function testDelete()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.core.services.chat_commands');
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
            ->willReturn(true);
        $commands->expects($this->once())
            ->method('parseParameters')
            ->with($cmdText)
            ->willReturn($cmdText);
        $commands->expects($this->once())->method('execute');

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.core.services.chat_commands');
        $chatCommandsMock
            ->expects($this->once())
            ->method('getChatCommand')
            ->with('test')
            ->willReturn($commands);

        $this->getDataProvider()->onPlayerChat('test', 'test', "/test $cmdText", true);
    }

    public function testChat()
    {
        $cmdText = 'value1 value2';

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.core.services.chat_commands');
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
        $chatCommandsMock = $this->container->get('expansion.core.services.chat_commands');
        $chatCommandsMock
            ->expects($this->once())
            ->method('getChatCommand')
            ->with('test')
            ->willReturn(null);

        $this->getDataProvider()->onPlayerChat('test', 'test', "/test $cmdText", true);
    }



    public function testVersionCommand()
    {
        $cmdText = 'value1 value2';

        /** @var \PHPUnit_Framework_MockObject_MockObject $chatCommandsMock */
        $chatCommandsMock = $this->container->get('expansion.core.services.chat_commands');
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
        return $this->container->get('expansion.core.data_providers.chat_command_data_provider');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getChatNotificationMock()
    {
        return $this->container->get('expansion.core.helpers.chat_notification');
    }
}
