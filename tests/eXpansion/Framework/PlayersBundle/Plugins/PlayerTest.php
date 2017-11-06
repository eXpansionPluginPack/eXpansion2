<?php
/**
 * File PlayerTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\PlayersBundle\Plugins;

use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\ScriptMethods\GetScores;
use eXpansion\Framework\PlayersBundle\Plugins\Player;
use eXpansion\Framework\PlayersBundle\Repository\PlayerRepository;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class PlayerTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerRepository;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockGetScores;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerStorage;

    /** @var Player */
    protected $player;

    protected function setUp()
    {
        parent::setUp();

        $this->mockPlayerRepository = $this->getMockBuilder(PlayerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockGetScores = $this->getMockBuilder(GetScores::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockPlayerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->player = new Player(
            $this->mockPlayerRepository,
            $this->mockGetScores,
            $this->mockPlayerStorage
        );
    }

    public function testLoad()
    {
        $this->mockPlayerRepository
            ->expects($this->exactly(2))
            ->method('findByLogin')
            ->with('toto-1')
            ->willReturn(null);
        $this->mockPlayerRepository
            ->expects($this->once())
            ->method('save');

        $this->mockPlayerStorage
            ->expects($this->once())
            ->method('getOnline')
            ->willReturn([$this->getPlayer('toto-1', false)]);

        $this->assertNull($this->player->getPlayer('toto-1'));

        $this->player->setStatus(true);

        $this->assertNotNull($this->player->getPlayer('toto-1'));
    }

    public function testOnNewPlayerConnect()
    {
        $this->mockPlayerRepository
            ->expects($this->exactly(2))
            ->method('findByLogin')
            ->with('toto-1')
            ->willReturn(null);
        $this->mockPlayerRepository
            ->expects($this->once())
            ->method('save');

        $this->assertNull($this->player->getPlayer('toto-1'));

        $this->player->onPlayerConnect($this->getPlayer('toto-1', false));

        $this->assertNotNull($this->player->getPlayer('toto-1'));
    }

    public function testExistingPlayerConnect()
    {
        $player = new \eXpansion\Framework\PlayersBundle\Entity\Player();
        $player->setLogin('toto-1');

        $this->mockPlayerRepository
            ->expects($this->once())
            ->method('findByLogin')
            ->with('toto-1')
            ->willReturn($player);
        $this->mockPlayerRepository
            ->expects($this->never())
            ->method('save');

        $this->player->onPlayerConnect($this->getPlayer('toto-1', false));
        $this->assertNotNull($this->player->getPlayer('toto-1'));
    }

    public function testDisconnectFreeMemory()
    {
        $this->mockPlayerRepository
            ->expects($this->exactly(3))
            ->method('findByLogin')
            ->with('toto-1')
            ->willReturn(null);
        $this->mockPlayerRepository
            ->expects($this->exactly(2))
            ->method('save');

        $this->assertNull($this->player->getPlayer('toto-1'));

        $this->player->onPlayerConnect($this->getPlayer('toto-1', false));
        $this->assertNotNull($this->player->getPlayer('toto-1'));

        $this->player->onPlayerDisconnect($this->getPlayer('toto-1', false), '');
        $this->assertNull($this->player->getPlayer('toto-1'));
    }

    public function testDisconnectOnlineTime()
    {
        $player = new \eXpansion\Framework\PlayersBundle\Entity\Player();
        $player->setLogin('toto-1');

        $this->mockPlayerRepository
            ->expects($this->once())
            ->method('findByLogin')
            ->with('toto-1')
            ->willReturn($player);
        $this->mockPlayerRepository
            ->expects($this->once())
            ->method('save')
            ->with([$player]);

        $this->player->onPlayerConnect($this->getPlayer('toto-1', false));
        sleep(1);
        $this->player->onPlayerDisconnect($this->getPlayer('toto-1', false), '');

        $this->assertTrue($player->getOnlineTime() > 0);
    }

    public function testOnMatchEndWin()
    {
        $player = new \eXpansion\Framework\PlayersBundle\Entity\Player();
        $player->setLogin('toto-1');

        $this->mockPlayerRepository
            ->expects($this->once())
            ->method('findByLogin')
            ->with('toto-1')
            ->willReturn($player);
        $this->mockPlayerRepository
            ->expects($this->exactly(2))
            ->method('save')
            ->with([$player]);

        $this->mockGetScores
            ->expects($this->once())
            ->method('get')
            ->willReturnCallback(function ($call) {
                $call(['winnerplayer' => 'toto-1']);
            });

        $this->player->onPlayerConnect($this->getPlayer('toto-1', false));
        $this->player->onEndMatchEnd(0, 0);
    }

    public function testEmptyMethods()
    {
        $player = $this->getPlayer('toto-1', false);

        $this->player->onPlayerInfoChanged($player, $player);
        $this->player->onPlayerAlliesChanged($player, $player);
        $this->player->onStartMatchStart(0, 0);
        $this->player->onStartMatchEnd(0, 0);
        $this->player->onEndMatchStart(0, 0);
        $this->player->onStartTurnStart(0, 0);
        $this->player->onStartTurnEnd(0, 0);
        $this->player->onEndTurnStart(0, 0);
        $this->player->onEndTurnEnd(0, 0);
        $this->player->onStartRoundStart(0, 0);
        $this->player->onStartRoundEnd(0, 0);
        $this->player->onEndRoundStart(0, 0);
        $this->player->onEndRoundEnd(0, 0);
    }
}
