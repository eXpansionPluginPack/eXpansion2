<?php


namespace Tests\eXpansion\Core\Storage;

use eXpansion\Core\Storage\Data\Player;
use eXpansion\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Structures\PlayerDetailedInfo;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;
use Tests\eXpansion\Core\TestCore;

class PlayerStorageTest extends TestCore
{
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
        $connectionMock = $this->container->get('expansion.core.services.dedicated_connection');
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

    protected function getPlayer($login, $spectator)
    {
        $playerI = new PlayerInfo();
        $playerI->isServer = false;
        $playerI->spectator = $spectator;
        $playerD = new PlayerDetailedInfo();
        $playerD->login = $login;
        $playerD->clientVersion = 'client-test';
        $playerD->nickName = '$fff' . $login;

        $player = new Player();
        $player->merge($playerI);
        $player->merge($playerD);

        return $player;
    }

    /**
     *
     * @return PlayerStorage
     */
    protected function getPlayerStorage()
    {
        return $this->container->get('expansion.core.storage.player');
    }
}
