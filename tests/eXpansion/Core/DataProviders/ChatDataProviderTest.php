<?php


namespace Tests\eXpansion\Core\DataProviders;

use eXpansion\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Core\Storage\Data\Player;
use Tests\eXpansion\Core\TestCore;

class ChatDataProviderTest extends TestCore
{
    public function testOnPlayerChat()
    {
        $player = new Player();
        $this->container->set('expansion.core.storage.player', $this->getMockPlayerStorage($player));

        $plugin = $this->createMock(ChatDataListenerInterface::class);
        $plugin->method('onPlayerChat')
            ->withConsecutive([$player, 'Chat text']);

        /** @var ChatDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.core.data_providers.chat_data_provider');
        $dataProvider->registerPlugin('test', $plugin);

        $dataProvider->onPlayerChat('test', 'test', 'Chat text', false);
    }

    public function testRemovePlugin()
    {
        $player = new Player();
        $this->container->set('expansion.core.storage.player', $this->getMockPlayerStorage($player));

        $pluginA = $this->createMock(ChatDataListenerInterface::class);
        $pluginA->expects($this->once())
            ->method('onPlayerChat')
            ->withConsecutive([$player, 'Chat text']);

        $pluginB = $this->createMock(ChatDataListenerInterface::class);
        $pluginB->expects($this->never())
            ->method('onPlayerChat');

        /** @var ChatDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.core.data_providers.chat_data_provider');
        $dataProvider->registerPlugin('testA', $pluginA);
        $dataProvider->registerPlugin('testB', $pluginB);
        $dataProvider->deletePlugin('testB');

        $dataProvider->onPlayerChat('test', 'test', 'Chat text', false);

        $dataProvider->deletePlugin('testA');
        $dataProvider->onPlayerChat('test', 'test', 'Chat text', false);
    }

}
