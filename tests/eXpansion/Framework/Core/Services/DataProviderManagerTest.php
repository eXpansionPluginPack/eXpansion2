<?php


namespace Tests\eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\DataProviders\ChatDataProvider;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\DataProviders\MapDataProvider;
use eXpansion\Framework\Core\DataProviders\PlayerDataProvider;
use eXpansion\Framework\Core\Exceptions\DataProvider\UncompatibleException;
use eXpansion\Framework\Core\Services\DataProviderManager;

use eXpansion\Framework\Core\Services\PluginManager;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use Maniaplanet\DedicatedServer\Structures\GameInfos;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;


class DataProviderManagerTest extends TestCore
{
    use PlayerDataTrait;

    protected function setUp()
    {
        parent::setUp();

        $gameInfos = new GameInfos();
        $gameInfos->scriptName = 'TimeAttack.script.txt';

        $gameDataStorageMock = $this->getMockBuilder(GameDataStorage::class)->disableOriginalConstructor()->getMock();
        $gameDataStorageMock->method('getTitle')->willReturn('TM');
        $gameDataStorageMock->method('getGameModeCode')->willReturn('script');
        $gameDataStorageMock->method('getGameInfos')->willReturn($gameInfos);

        $this->container->set('expansion.framework.core.storage.game_data', $gameDataStorageMock);
    }


    protected function prepareProviders()
    {
        $dataProviderManager = $this->getDataProviderManager();

        $mockProvider = $this->createMock(ChatDataProvider::class);
        $this->container->set('dp1-1', $mockProvider);

        $mockProvider = $this->createMock(ChatDataProvider::class);
        $this->container->set('dp1-2', $mockProvider);

        $mockProvider = $this->createMock(MapDataProvider::class);
        $this->container->set('dp2-2', $mockProvider);

        $listner = ['onPlayerChat' => 'onPlayerChat'];

        $compatibilities = [];
        $compatibilities[] = $this->getCompatibility('TM', 'script', 'TimeAttack.script.txt');
        $dataProviderManager
            ->registerDataProvider('dp1-1', 'dp1', ListenerInterfaceMpLegacyChat::class, $compatibilities, $listner);

        $compatibilities = [];
        $compatibilities[] = $this->getCompatibility('TM');
        $dataProviderManager
            ->registerDataProvider('dp1-2', 'dp1', ListenerInterfaceMpLegacyChat::class, $compatibilities, $listner);

        $compatibilities = [];
        $compatibilities[] = $this->getCompatibility('TM2');
        $dataProviderManager
            ->registerDataProvider('dp2-2', 'dp2', ListenerInterfaceMpLegacyChat::class, $compatibilities, $listner);
    }

    public function testPreferenceDataProvider()
    {
        $dataProviderManager = $this->getDataProviderManager();
        $this->prepareProviders();

        $this->assertEquals(
            'dp1-1',
            $dataProviderManager->getCompatibleProviderId('dp1', 'TM', 'script', 'TimeAttack.script.txt')
        );

        $this->assertEquals(
            'dp1-2',
            $dataProviderManager->getCompatibleProviderId('dp1', 'TM', 'script2', 'TimeAttack.script.txt')
        );

        $this->assertEquals(
            'dp2-2',
            $dataProviderManager->getCompatibleProviderId('dp2', 'TM2', 'script2', 'TimeAttack.script.txt')
        );

        $this->assertNull(
            $dataProviderManager->getCompatibleProviderId('dp1', 'TM3', 'script2', 'TimeAttack.script.txt')
        );

        $this->assertTrue(
            $dataProviderManager->isProviderCompatible('dp1', 'TM', 'script2', 'TimeAttack.script.txt')
        );
    }

    public function testRegisterPlugin()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->getDataProviderManager();
        $player = $this->getPlayer('test1', false);

        $pluginMock = $this->createMock(ListenerInterfaceMpLegacyChat::class);
        $this->container->set('p1', $pluginMock);

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('dp1-1');
        $dataProviderMock->expects($this->once())->method('registerPlugin')->with('p1', $pluginMock);
        // $dataProviderMock->expects($this->once())->method('onChat')->withAnyParameters();

        $dataProviderMock->expects($this->once())->method('registerPlugin')->withConsecutive(['p1', $pluginMock]);

        $dataProviderManager->registerPlugin('dp1', 'p1', 'TM', 'script', 'TimeAttack.script.txt');
    }

    public function testRegisterWrongPlugin()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->getDataProviderManager();

        $pluginMock = $this->createMock(ListenerInterfaceMpLegacyPlayer::class);
        $this->container->set('p1', $pluginMock);

        $this->expectException(UncompatibleException::class);

        $dataProviderManager->registerPlugin('dp1', 'p1', 'TM', 'script', 'TimeAttack.script.txt');
    }

    public function testDispatch()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->getDataProviderManager();

        $connectionMock = $this->container->get('expansion.service.dedicated_connection');
        $connectionMock->method('getPlayerList')
            ->withAnyParameters()
            ->willReturn([new PlayerInfo()]);

        $pManagerMock = $this->createMock(PluginManager::class);
        $pManagerMock->expects($this->any())->method('isPluginEnabled')->willReturn(true);

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('dp1-1');
        $dataProviderMock->expects($this->once())->method('onPlayerChat')->withAnyParameters();

        $dataProviderMock2 = $this->container->get('dp1-2');
        $dataProviderMock2->expects($this->never())->method('onPlayerChat');

        $dataProviderManager->init($pManagerMock);
        $dataProviderManager->dispatch('onPlayerChat', ['test', 'test2', false]);
    }


    protected function getCompatibility(
        $title,
        $mode = DataProviderManager::COMPATIBLE_ALL,
        $script = DataProviderManager::COMPATIBLE_ALL
    ){
        return [
            'title' => $title,
            'mode' => $mode,
            'script' => $script
        ];
    }

    /**
     *
     * @return DataProviderManager
     */
    protected function getDataProviderManager()
    {


        return $this->container->get('expansion.service.data_provider_manager');
    }
}
