<?php


namespace Tests\eXpansion\Framework\Core\Storage;

use eXpansion\Framework\Core\Storage\Data\PlayerFactory;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Structures\PlayerDetailedInfo;
use Maniaplanet\DedicatedServer\Structures\PlayerInfo;
use Psr\Log\LoggerInterface;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class PlayerStorageTest extends TestCore
{
    use PlayerDataTrait;

    protected $playerStorage;

    protected function setUp()
    {
        parent::setUp();

        $this->playerStorage = new PlayerStorage(
            $this->mockConnectionFactory,
            $this->container->get(PlayerFactory::class),
            $this->container->get('logger'),
            $this->mockConsole
        );
    }


    public function testGetPlayerInfo()
    {
        $playerI = new PlayerInfo();
        $playerI->login = 'test';
        $playerI->isServer = false;
        $playerD = new PlayerDetailedInfo();
        $playerD->login = 'test';
        $playerD->clientVersion = 'client-test';
        $playerD->nickName = '$ffftest';


        $connectionMock = $this->mockConnection;
        $connectionMock->method('getPlayerInfo')
            ->withConsecutive(['test'])
            ->willReturn($playerI);
        $connectionMock->method('getDetailedPlayerInfo')
            ->withConsecutive(['test'])
            ->willReturn($playerD);

        $player = $this->playerStorage->getPlayerInfo('test');

        $this->assertEquals('test', $player->getLogin());
        $this->assertEquals('$ffftest', $player->getNickName());
        $this->assertEquals('client-test', $player->getClientVersion());
        $this->assertFalse($player->isIsServer());
    }

    public function testOnPlayerConnect()
    {
        $player1 = $this->getPlayer('test-1', false);
        $player2 = $this->getPlayer('test-2', false);

        $this->playerStorage->onPlayerConnect($player1);
        $this->playerStorage->onPlayerConnect($player2);

        $players = $this->playerStorage->getPlayers();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);

        $players = $this->playerStorage->getOnline();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);
    }

    public function testOnPlayerDisconnect()
    {
        $this->testOnPlayerConnect();
        $player1 = $this->getPlayer('test-2', false);

        $this->playerStorage->onPlayerDisconnect($player1, '');

        $toRemove = $this->playerStorage->getPlayersToRemove();
        $this->assertSame(['test-2'], $toRemove);

        $this->playerStorage->onPreLoop();

        $players = $this->playerStorage->getPlayers();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayNotHasKey('test-2', $players);

        $players = $this->playerStorage->getOnline();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayNotHasKey('test-2', $players);
    }

    public function testOnPlayerInfoChanged()
    {
        $this->testOnPlayerConnect();
        $playerOld = $this->playerStorage->getPlayerInfo('test-2');
        $player2 = $this->getPlayer('test-2', true);

        $this->playerStorage->onPlayerInfoChanged($playerOld, $player2);

        $players = $this->playerStorage->getPlayers();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayNotHasKey('test-2', $players);

        $players = $this->playerStorage->getSpectators();
        $this->assertArrayNotHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);

        $players = $this->playerStorage->getOnline();
        $this->assertArrayHasKey('test-1', $players);
        $this->assertArrayHasKey('test-2', $players);
    }

    public function testOnplayerAlliesChanged()
    {
        $this->testOnPlayerConnect();
        $playerOld = $this->playerStorage->getPlayerInfo('test-2');
        $player2 = $this->getPlayer('test-2', true);

        $this->playerStorage->onPlayerAlliesChanged($playerOld, $player2);

        $this->assertEquals($player2, $this->playerStorage->getPlayerInfo('test-2'));
    }
}
