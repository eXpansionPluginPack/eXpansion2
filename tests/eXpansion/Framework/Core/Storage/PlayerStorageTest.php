<?php


namespace Tests\eXpansion\Framework\Core\Storage;

use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Structures\PlayerDetailedInfo;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class PlayerStorageTest extends TestCore
{
    use PlayerDataTrait;

    public function testGetPlayerInfo()
    {
        $playerI = new PlayerInfo();
        $playerI->login = 'test';
        $playerI->isServer = false;
        $playerD = new PlayerDetailedInfo();
        $playerD->login = 'test';
        $playerD->clientVersion = 'client-test';
        $playerD->nickName = '$ffftest';


        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.framework.core.services.dedicated_connection');
        $connectionMock->method('getPlayerInfo')
            ->withConsecutive(['test'])
            ->willReturn($playerI);
        $connectionMock->method('getDetailedPlayerInfo')
            ->withConsecutive(['test'])
            ->willReturn($playerD);

        $player = $this->getPlayerStorage()->getPlayerInfo('test');

        $this->assertEquals('test', $player->getLogin());
        $this->assertEquals('$ffftest', $player->getNickName());
        $this->assertEquals('client-test', $player->getClientVersion());
        $this->assertFalse($player->isIsServer());
    }

    public function testOnPlayerConnect()
    {
        $player1 = $this->getPlayer('test-1', false);
        $player2 = $this->getPlayer('test-2', false);

        $this->getPlayerStorage()->onPlayerConnect($player1);
        $this->getPlayerStorage()->onPlayerConnect($player2);

        $players = $this->getPlayerStorage()->getPlayers();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);

        $players = $this->getPlayerStorage()->getOnline();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);
    }

    public function testOnPlayerDisconnect()
    {
        $this->testOnPlayerConnect();
        $player1 = $this->getPlayer('test-2', false);

        $this->getPlayerStorage()->onPlayerDisconnect($player1, '');

        $toRemove = $this->getPlayerStorage()->getPlayersToRemove();
        $this->assertSame(['test-2'], $toRemove);

        $this->getPlayerStorage()->onPreLoop();

        $players = $this->getPlayerStorage()->getPlayers();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayNotHasKey('test-2', $players);

        $players = $this->getPlayerStorage()->getOnline();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayNotHasKey('test-2', $players);
    }

    public function testOnPlayerInfoChanged()
    {
        $this->testOnPlayerConnect();
        $playerOld = $this->getPlayerStorage()->getPlayerInfo('test-2');
        $player2 = $this->getPlayer('test-2', true);

        $this->getPlayerStorage()->onPlayerInfoChanged($playerOld, $player2);

        $players = $this->getPlayerStorage()->getPlayers();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayNotHasKey('test-2', $players);

        $players = $this->getPlayerStorage()->getSpectators();
        $this->assertArrayNotHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);

        $players = $this->getPlayerStorage()->getOnline();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);
    }

    public function testOnplayerAlliesChanged()
    {
        $this->testOnPlayerConnect();
        $playerOld = $this->getPlayerStorage()->getPlayerInfo('test-2');
        $player2 = $this->getPlayer('test-2', true);

        $this->getPlayerStorage()->onPlayerAlliesChanged($playerOld, $player2);

        $this->assertEquals($player2, $this->getPlayerStorage()->getPlayerInfo('test-2'));
    }

    /**
     *
     * @return PlayerStorage
     */
    protected function getPlayerStorage()
    {
        return $this->container->get('expansion.framework.core.storage.player');
    }
}
