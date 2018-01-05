<?php

namespace Tests\eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Exceptions\ChatCommand\CommandExistException;
use eXpansion\Framework\Core\Model\ChatCommand\ChatCommandInterface;
use eXpansion\Framework\Core\Model\ChatCommand\ChatCommandPlugin;
use eXpansion\Framework\Core\Services\ChatCommands;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\Model\TestMultiParameterChatCommand;

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

        $this->assertEquals([$commands,[]], $service->getChatCommand(['test']));
        $this->assertEquals([$commands,[]], $service->getChatCommand(['t']));
        $this->assertEquals([$commands,[]], $service->getChatCommand(['l']));

        $service->deletePlugin('test');
        $this->assertEquals([null,[]], $service->getChatCommand(['test']));
        $this->assertEquals([null,[]], $service->getChatCommand(['t']));
        $this->assertEquals([null,[]], $service->getChatCommand(['l']));
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

    public function testMultiParameterChatCommand()
    {
        $cmdText = 'login "here goes reason"';
        $command = new TestMultiParameterChatCommand('admin utest', []);

        $service = $this->getChatCommandService();
        $service->registerPlugin('test', new ChatCommandPlugin([$command]));

        list($fcommand, $parameter) = $service->getChatCommand(explode(' ', "admin utest $cmdText"));

        $this->assertEquals($command, $fcommand);
        $this->assertEquals(['login', '"here', 'goes', 'reason"'], $parameter);
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
        return $this->container->get(ChatCommands::class);
    }
}
