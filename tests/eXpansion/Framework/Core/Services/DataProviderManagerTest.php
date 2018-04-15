<?php


namespace Tests\eXpansion\Framework\Core\Services;

use eXpansion\Framework\Core\Exceptions\DataProvider\UncompatibleException;
use eXpansion\Framework\Core\Helpers\CompatibleFetcher;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DataProviderManager;
use eXpansion\Framework\Core\Services\PluginManager;
use eXpansion\Framework\Core\Storage\GameDataStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\ChatDataProvider;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\GameManiaplanet\DataProviders\MapDataProvider;
use Maniaplanet\DedicatedServer\Structures\GameInfos;
use Maniaplanet\DedicatedServer\Structures\Map;
use Maniaplanet\DedicatedServer\Structures\PlayerDetailedInfo;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;
use Maniaplanet\DedicatedServer\Structures\Version;
use Psr\Log\LoggerInterface;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;


class DataProviderManagerTest extends TestCore
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockGameDataStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $compatibleFetcher;

    /** @var DataProviderManager */
    protected $dataProviderManager;

    protected function setUp()
    {
        parent::setUp();

        $gameInfos = new GameInfos();
        $gameInfos->scriptName = 'timeattack.script.txt';

        $this->mockGameDataStorage = $this->getMockBuilder(GameDataStorage::class)->disableOriginalConstructor()->getMock();
        $this->mockGameDataStorage->method('getTitle')->willReturn('TM');
        $this->mockGameDataStorage->method('getGameModeCode')->willReturn('script');
        $this->mockGameDataStorage->method('getGameInfos')->willReturn($gameInfos);
        $this->mockGameDataStorage->method('getVersion')->willReturn(new Version());

        $this->compatibleFetcher = new CompatibleFetcher($this->container->getParameter('expansion.storage.gamedata.titles'));

        $this->dataProviderManager = new DataProviderManager(
            $this->container,
            $this->mockGameDataStorage,
            $this->container->get(Console::class),
            $this->getMockBuilder(LoggerInterface::class)->getMock(),
            $this->compatibleFetcher
        );
    }


    protected function prepareProviders()
    {
        $dataProviderManager = $this->dataProviderManager;

        $mockProvider = $this->createMock(ChatDataProvider::class);
        $this->container->set('dp1-1', $mockProvider);

        $mockProvider = $this->createMock(ChatDataProvider::class);
        $this->container->set('dp1-2', $mockProvider);

        $mockProvider = $this->createMock(MapDataProvider::class);
        $this->container->set('dp2-2', $mockProvider);

        $listner = ['onPlayerChat' => 'onPlayerChat'];

        $compatibilities = [];
        $compatibilities[] = $this->getCompatibility('TM', 'script', 'timeattack.script.txt');
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
        $dataProviderManager = $this->dataProviderManager;
        $this->prepareProviders();
        $map = new Map();

        $this->assertEquals(
            'dp1-1',
            $dataProviderManager->getCompatibleProviderId('dp1', 'TM', 'script', 'timeattack.script.txt', $map)
        );

        $this->assertEquals(
            'dp1-2',
            $dataProviderManager->getCompatibleProviderId('dp1', 'TM', 'script2', 'timeattack.script.txt', $map)
        );

        $this->assertEquals(
            'dp2-2',
            $dataProviderManager->getCompatibleProviderId('dp2', 'TM2', 'script2', 'timeattack.script.txt', $map)
        );

        $this->assertNull(
            $dataProviderManager->getCompatibleProviderId('dp1', 'TM3', 'script2', 'timeattack.script.txt', $map)
        );

        $this->assertTrue(
            $dataProviderManager->isProviderCompatible('dp1', 'TM', 'script2', 'timeattack.script.txt', $map)
        );
    }

    public function testRegisterPlugin()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->dataProviderManager;
        $player = $this->getPlayer('test1', false);
        $map = new Map();

        $pluginMock = $this->createMock(ListenerInterfaceMpLegacyChat::class);
        $this->container->set('p1', $pluginMock);

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('dp1-1');
        $dataProviderMock->expects($this->once())->method('registerPlugin')->with('p1', $pluginMock);
        // $dataProviderMock->expects($this->once())->method('onChat')->withAnyParameters();

        $dataProviderMock->expects($this->once())->method('registerPlugin')->withConsecutive(['p1', $pluginMock]);

        $dataProviderManager->registerPlugin('dp1', 'p1', 'TM', 'script', 'timeattack.script.txt', $map);
    }

    public function testRegisterWrongPlugin()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->dataProviderManager;

        $pluginMock = $this->createMock(ListenerInterfaceMpLegacyPlayer::class);
        $this->container->set('p1', $pluginMock);

        $this->expectException(UncompatibleException::class);

        $dataProviderManager->registerPlugin('dp1', 'p1', 'TM', 'script', 'timeattack.script.txt', new Map());
    }

    public function testDispatch()
    {
        $this->prepareProviders();
        $dataProviderManager = $this->dataProviderManager;
        $dataProviderManager->reset(
            $this->getMockBuilder(PluginManager::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(Map::class)->getMock()
        );

        $connectionMock = $this->mockConnection;
        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock->method('getPlayerList')
            ->withAnyParameters()
            ->willReturn([new PlayerInfo()]);
        $connectionMock->method('getVersion')
            ->withAnyParameters()
            ->willReturn([new Version()]);
        $connectionMock->method('getPlayerInfo')
            ->withAnyParameters()
            ->willReturn(new PlayerInfo());
        $connectionMock->method('getDetailedPlayerInfo')
            ->withAnyParameters()
            ->willReturn(new PlayerDetailedInfo());

        $pManagerMock = $this->createMock(PluginManager::class);
        $pManagerMock->expects($this->any())->method('isPluginEnabled')->willReturn(true);

        /** @var \PHPUnit_Framework_MockObject_MockObject $dataProviderMock */
        $dataProviderMock = $this->container->get('dp1-1');
        $dataProviderMock->expects($this->once())->method('onPlayerChat')->withAnyParameters();

        $dataProviderMock2 = $this->container->get('dp1-2');
        $dataProviderMock2->expects($this->never())->method('onPlayerChat');

        $dataProviderManager->reset($pManagerMock, new Map());
        $dataProviderManager->dispatch('onPlayerChat', ['test', 'test2', false]);
    }


    protected function getCompatibility(
        $title,
        $mode = DataProviderManager::COMPATIBLE_ALL,
        $script = DataProviderManager::COMPATIBLE_ALL
    ) {
        return [
            'title' => $title,
            'gamemode' => $mode,
            'script' => $script,
        ];
    }
}
