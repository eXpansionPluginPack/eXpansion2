<?php


namespace Tests\eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\DataProviders\Listener\ChatDataListenerInterface;
use eXpansion\Framework\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Framework\Core\DataProviders\PlayerDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Tests\eXpansion\Framework\Core\TestCore;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;


class PlayerDataProviderTest extends TestCore
{
    use PlayerDataTrait;

    protected $player;

    protected function setUp()
    {
        parent::setUp();

        $this->player = new PlayerInfo();
    }

    public function testOnRun()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.framework.core.services.dedicated_connection');
        $connectionMock->method('getPlayerList')
            ->withAnyParameters()
            ->willReturn([$this->player]);

        $player = new Player();
        $this->container->set('expansion.framework.core.storage.player', $this->getMockPlayerStorage($player));

        $plugin = $this->createMock(PlayerDataListenerInterface::class);
        $plugin->expects($this->once())
            ->method('onPlayerConnect')
            ->withConsecutive([$player]);

        /** @var PlayerDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.player_data_provider');
        $dataProvider->registerPlugin('p1', $plugin);

        $dataProvider->onRun();
    }

    public function testOnPlayerConnect()
    {
        $player = $this->getPlayer('test', false);
        $this->container->set('expansion.framework.core.storage.player', $this->getMockPlayerStorage($player));

        $plugin = $this->createMock(PlayerDataListenerInterface::class);
        $plugin->expects($this->once())
            ->method('onPlayerConnect')
            ->withConsecutive([$player]);


        /** @var PlayerDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.player_data_provider');
        $dataProvider->registerPlugin('p1', $plugin);

        $dataProvider->onPlayerConnect('test', false);
    }

    public function testOnPlayerConnectDisconnectFast()
    {
        $playerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $playerStorage->method('getPlayerInfo')
            ->willThrowException(new \Exception());
        $this->container->set('expansion.framework.core.storage.player',$playerStorage);

        $plugin = $this->createMock(PlayerDataListenerInterface::class);
        $plugin->expects($this->never())
            ->method('onPlayerConnect');

        /** @var PlayerDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.player_data_provider');
        $dataProvider->registerPlugin('p1', $plugin);

        $dataProvider->onPlayerConnect('test', false);
    }

    public function testOnPlayerDisconnect()
    {
        $player = $this->getPlayer('test', false);
        $this->container->set('expansion.framework.core.storage.player', $this->getMockPlayerStorage($player));

        $plugin = $this->createMock(PlayerDataListenerInterface::class);
        $plugin->expects($this->once())
            ->method('onPlayerDisconnect')
            ->withConsecutive([$player]);

        /** @var PlayerDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.player_data_provider');
        $dataProvider->registerPlugin('p1', $plugin);

        $dataProvider->onPlayerDisconnect('test', false);
    }

    public function testOnPlayerInfoChanged()
    {
        $player = $this->getPlayer('test', false);
        $this->container->set('expansion.framework.core.storage.player', $this->getMockPlayerStorage($player));

        $plugin = $this->createMock(PlayerDataListenerInterface::class);
        $plugin->expects($this->once())
            ->method('onPlayerInfoChanged')
            ->withConsecutive([$player]);


        /** @var PlayerDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.player_data_provider');
        $dataProvider->registerPlugin('p1', $plugin);

        $dataProvider->onPlayerInfoChanged(['Login' => 'test']);
    }

    public function testOnPlayerAlliesChanged()
    {
        $player = $this->getPlayer('test', false);
        $this->container->set('expansion.framework.core.storage.player', $this->getMockPlayerStorage($player));

        $plugin = $this->createMock(PlayerDataListenerInterface::class);
        $plugin->expects($this->once())
            ->method('onPlayerAlliesChanged')
            ->withConsecutive([$player, $player]);


        /** @var PlayerDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.player_data_provider');
        $dataProvider->registerPlugin('p1', $plugin);

        $dataProvider->onPlayerAlliesChanged('test');
    }
}
