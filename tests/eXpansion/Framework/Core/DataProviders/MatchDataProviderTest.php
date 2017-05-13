<?php

namespace Tests\eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\DataProviders\Listener\MatchDataListenerInterface;
use eXpansion\Framework\Core\DataProviders\MatchDataProvider;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

/**
 * Class MatchDataProviderTest
 *
 * @package Tests\eXpansion\Framework\Core\DataProviders
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
        $this->container->set('expansion.framework.core.storage.player', $this->playerStorageMock);

        $this->pluginMock = $this->createMock(MatchDataListenerInterface::class);
        $this->matchDataProvider = $this->container->get('expansion.framework.core.data_providers.match_data_provider');

        $this->matchDataProvider->registerPlugin('test', $this->pluginMock);
    }

    public function testOnBeginMatch()
    {
        $this->pluginMock->expects($this->once())->method('onBeginMatch')->withConsecutive([]);
        $this->matchDataProvider->onBeginMatch();
    }

    public function testOnEndMatch()
    {
        $this->pluginMock->expects($this->once())->method('onEndMatch')->withConsecutive([]);
        $this->matchDataProvider->onEndMatch();
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

    public function testOnBeginRound()
    {
        $this->pluginMock->expects($this->once())->method('onBeginRound')->withConsecutive([]);
        $this->matchDataProvider->onBeginRound();
    }

    public function testOnEndRound()
    {
        $this->pluginMock->expects($this->once())->method('onEndRound')->withConsecutive([]);
        $this->matchDataProvider->OnEndRound();
    }

    public function testOnPlayerFinish()
    {
        $data = "1.5";
        $this->pluginMock
            ->expects($this->once())
            ->method("onPlayerFinish")
            ->withConsecutive([$this->player, (float)$data]);

        $this->matchDataProvider->onPlayerFinish(1, 'test', $data);
    }

    public function testOnPlayerCheckpoint()
    {
        $this->pluginMock
            ->expects($this->once())
            ->method("onPlayerCheckpoint")
            ->withConsecutive([$this->player, 1200, 1, 5]);

        $this->matchDataProvider->onPlayerCheckpoint(1, 'test', 1200, 1, 5);
    }


    protected function getMapArray()
    {
        return [
            'uId' => 'test',
            'name' => 'name',
        ];
    }
}
