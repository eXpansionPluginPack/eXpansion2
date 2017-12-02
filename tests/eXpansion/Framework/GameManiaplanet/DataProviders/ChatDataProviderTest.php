<?php


namespace Tests\eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\GameManiaplanet\DataProviders\ChatDataProvider;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Tests\eXpansion\Framework\Core\TestCore;

class ChatDataProviderTest extends TestCore
{
    public function testOnPlayerChat()
    {
        $player = new Player();
        $this->container->set(PlayerStorage::class, $this->getMockPlayerStorage($player));

        $plugin = $this->createMock(ListenerInterfaceMpLegacyChat::class);
        $plugin->method('onPlayerChat')
            ->withConsecutive([$player, 'Chat text']);

        /** @var ChatDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.chat_data_provider');
        $dataProvider->registerPlugin('test', $plugin);

        $dataProvider->onPlayerChat('test', 'test', 'Chat text', false);
    }

    public function testRemovePlugin()
    {
        $player = new Player();
        $this->container->set(PlayerStorage::class, $this->getMockPlayerStorage($player));

        $pluginA = $this->createMock(ListenerInterfaceMpLegacyChat::class);
        $pluginA->expects($this->once())
            ->method('onPlayerChat')
            ->withConsecutive([$player, 'Chat text']);

        $pluginB = $this->createMock(ListenerInterfaceMpLegacyChat::class);
        $pluginB->expects($this->never())
            ->method('onPlayerChat');

        /** @var ChatDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.chat_data_provider');
        $dataProvider->registerPlugin('testA', $pluginA);
        $dataProvider->registerPlugin('testB', $pluginB);
        $dataProvider->deletePlugin('testB');

        $dataProvider->onPlayerChat(1, 'test', 'Chat text', false);

        $dataProvider->deletePlugin('testA');
        $dataProvider->onPlayerChat(1, 'test', 'Chat text', false);
    }

}
