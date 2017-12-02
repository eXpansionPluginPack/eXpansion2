<?php

namespace Tests\eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\MapDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

/**
 * Class MatchDataProviderTest
 *
 * @package Tests\eXpansion\Framework\GameManiaplanet\DataProviders
 * @author Oliver de Cramer
 */
class MatchDataProviderTest extends TestCore
{
    use PlayerDataTrait;

    /** @var  Player */
    protected $player;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $pluginMock;

    /** @var  PlayerStorage */
    protected $playerStorageMock;

    /** @var MatchDataProvider */
    protected $matchDataProvider;

    protected function setUp()
    {
        parent::setUp();

        $this->player = $this->getPlayer('test', false);
        $this->playerStorageMock = $this->getMockPlayerStorage($this->player);
        $this->container->set('expansion.storage.player', $this->playerStorageMock);

        $this->pluginMock = $this->createMock(ListenerInterfaceMpLegacyMap::class);
        $this->matchDataProvider = $this->container->get('expansion.framework.core.data_providers.match_data_provider');

        $this->matchDataProvider->registerPlugin('test', $this->pluginMock);
    }

    public function testOnBeginMap()
    {
        $mapArray = $this->getMapArray();
        $this->pluginMock->expects($this->once())->method('onBeginMap')->withAnyParameters();
        $this->matchDataProvider->onBeginMap($mapArray);
    }

    public function testOnEndMap()
    {
        $mapArray = $this->getMapArray();
        $this->pluginMock->expects($this->once())->method('onEndMap')->withAnyParameters();
        $this->matchDataProvider->onEndMap($mapArray);
    }

    protected function getMapArray()
    {
        return [
            'uId' => 'test',
            'name' => 'name',
        ];
    }
}
