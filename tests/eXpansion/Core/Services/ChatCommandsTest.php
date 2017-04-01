<?php

namespace Tests\eXpansion\Core\Services;

use eXpansion\Core\Exceptions\ChatCommand\CommandExistException;
use eXpansion\Core\Model\ChatCommand\ChatCommandInterface;
use eXpansion\Core\Model\ChatCommand\ChatCommandPlugin;
use eXpansion\Core\Services\ChatCommands;
use Tests\eXpansion\Core\TestCore;
use Tests\eXpansion\Core\TestHelpers\Model\TestChatCommand;

class ChatCommandsTest extends TestCore
{
    public function testRegister()
    {
        $commands = $this->createMock(ChatCommandInterface::class);
        $commands->expects($this->atLeastOnce())->method('getCommand')->willReturn('test');
        $commands->expects($this->atLeastOnce())->method('getAliases')->willReturn(['t', 'l']);

        $plugin = new ChatCommandPlugin([$commands]);

        $service = $this->getChatCommandService();

        $service->registerPlugin('test', $plugin);
        $this->assertEquals($commands, $service->getChatCommand('test'));
        $this->assertEquals($commands, $service->getChatCommand('t'));
        $this->assertEquals($commands, $service->getChatCommand('l'));

        $service->deletePlugin('test');
        $this->assertNull($service->getChatCommand('test'));
        $this->assertNull($service->getChatCommand('t'));
        $this->assertNull($service->getChatCommand('l'));
    }

    public function testDoubleRegister()
    {
        $commands = $this->createMock(ChatCommandInterface::class);
        $commands->expects($this->atLeastOnce())->method('getCommand')->willReturn('test');
        $commands->expects($this->atLeastOnce())->method('getAliases')->willReturn(['t', 'l']);

        $plugin = new ChatCommandPlugin([$commands]);

        $service = $this->getChatCommandService();

        $this->expectException(CommandExistException::class);
        $service->registerPlugin('test', $plugin);
        $service->registerPlugin('test', $plugin);
    }

    public function testDelete()
    {
        // Deleting plugin that wasn't registered shuld do nothing.
        $service = $this->getChatCommandService();
        $service->deletePlugin('test');
    }

    /**
     * @return ChatCommands
     */
    protected function getChatCommandService()
    {
        return $this->container->get('expansion.core.services.chat_commands');
    }
}
