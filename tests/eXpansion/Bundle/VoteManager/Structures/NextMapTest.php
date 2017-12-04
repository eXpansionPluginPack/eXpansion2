<?php
/**
 * File Test.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\VoteManager\Structures;

use eXpansion\Bundle\VoteManager\Structures\NextMapVote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class NextMapTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConnection;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var NextMapVote */
    protected $vote;

    protected function setUp()
    {
        parent::setUp();

        $this->mockConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockChatNotification = $this->getMockBuilder(ChatNotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->vote = new NextMapVote(
            $this->getPlayer('starter', false),
            'test',
            30,
            0.57,
            $this->mockConnection,
            $this->mockChatNotification
        );
    }

    public function testVoteCastingPassed()
    {
        $this->vote->castYes('toto1');
        $this->vote->castYes('toto2');
        $this->vote->castNo('toto3');
        $this->assertEquals(NextMapVote::STATUS_RUNNING, $this->vote->getStatus());
        $this->vote->updateVote(time());

        $this->assertEquals(2, $this->vote->getYes());
        $this->assertEquals(1, $this->vote->getNo());
        $this->assertEquals(NextMapVote::STATUS_PASSED, $this->vote->getStatus());
    }

    public function testVoteCastingFailed()
    {
        $this->vote->castNo('toto1');
        $this->vote->castNo('toto2');
        $this->vote->castNo('toto3');
        $this->assertEquals(NextMapVote::STATUS_RUNNING, $this->vote->getStatus());
        $this->vote->updateVote(time());

        $this->assertEquals(0, $this->vote->getYes());
        $this->assertEquals(3, $this->vote->getNo());
        $this->assertEquals(NextMapVote::STATUS_FAILED, $this->vote->getStatus());
    }

    public function testNoVotes()
    {
        $this->assertEquals(NextMapVote::STATUS_RUNNING, $this->vote->getStatus());
        $this->vote->updateVote($this->vote->getStartTime() + 40);
        $this->assertEquals(NextMapVote::STATUS_FAILED, $this->vote->getStatus());
        $this->assertEquals(40, $this->vote->getElapsedTime());
    }

    public function testGetters()
    {
        $this->assertTrue((int) $this->vote->getStartTime() == $this->vote->getStartTime());
        $this->assertEquals(30, $this->vote->getTotalTime());
        $this->assertEquals(0.57, $this->vote->getRatio());
        $this->assertEquals('test', $this->vote->getType());
        $this->assertInstanceOf(Player::class, $this->vote->getPlayer());
        $this->assertNotEmpty($this->vote->getQuestion());
    }

    public function testExecutePassed()
    {
        $this->mockConnection->expects($this->once())->method('nextMap');
        $this->mockChatNotification->expects($this->once())->method('sendMessage');
        $this->vote->executeVotePassed();
        $this->vote->executeVoteFailed();
    }
}
