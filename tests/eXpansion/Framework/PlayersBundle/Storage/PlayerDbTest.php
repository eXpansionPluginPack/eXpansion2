<?php
/**
 * File PlayerDbTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\PlayersBundle\Storage;

use eXpansion\Framework\PlayersBundle\Plugins\Player;
use eXpansion\Framework\PlayersBundle\Storage\PlayerDb;

class PlayerDbTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerPlugin;

    /** @var \eXpansion\Framework\PlayersBundle\Model\Player */
    protected $playerDb;

    protected function setUp()
    {
        parent::setUp();

        $this->mockPlayerPlugin = $this->getMockBuilder(Player::class)->disableOriginalConstructor()->getMock();

        $this->playerDb = new PlayerDb($this->mockPlayerPlugin);
    }

    public function testGet()
    {
        $player = new \eXpansion\Framework\PlayersBundle\Model\Player();

        $this->mockPlayerPlugin
            ->expects($this->once())
            ->method('getPlayer')
            ->with('toto-1')
            ->willReturn($player);

        $this->assertEquals($player, $this->playerDb->get('toto-1'));
    }
}
