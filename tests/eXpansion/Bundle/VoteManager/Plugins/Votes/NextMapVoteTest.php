<?php
/**
 * File NextMapVoteTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\VoteManager\Plugins\Votes;

use eXpansion\Bundle\VoteManager\Plugins\Votes\NextMapVote;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class NextMapVoteTest extends TestCore
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var NextMapVote */
    protected $nextMapVote;


    protected function setUp()
    {
        parent::setUp();

        $this->mockPlayerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockChatNotification = $this->getMockBuilder(ChatNotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->nextMapVote = new NextMapVote(
            $this->getMockBuilder(DispatcherInterface::class)->getMock(),
            $this->mockPlayerStorage,
            $this->mockConnectionFactory,
            $this->mockChatNotification,
            30,
            0.57
        );
    }

    public function testVoteNoDecision()
    {
        $this->mockPlayerStorage->method('getOnline')->willReturn(['test1', 'test2', 'test3']);

        $player = $this->getPlayer('test', false);
        $this->nextMapVote->start($player, []);

        // A single person votes out of 3 no decision should be made.
        $this->nextMapVote->castYes('test1');
        $this->nextMapVote->update(time());

        $this->assertEquals(Vote::STATUS_RUNNING, $this->nextMapVote->getCurrentVote()->getStatus());
    }

    public function testVotePassedBeforeTimeout()
    {
        $this->mockConnection->expects($this->once())->method('nextMap');
        $this->mockChatNotification->expects($this->once())->method('sendMessage');

        $this->mockPlayerStorage->method('getOnline')->willReturn(['test1', 'test2', 'test3', 'test4']);

        $player = $this->getPlayer('test', false);
        $this->nextMapVote->start($player, []);

        // 3 person out of 4 votes yes pass vote before timeout.
        $this->nextMapVote->castYes('test1');
        $this->nextMapVote->castYes('test2');
        $this->nextMapVote->castYes('test3');
        $this->nextMapVote->update(time());

        $this->assertEquals(Vote::STATUS_PASSED, $this->nextMapVote->getCurrentVote()->getStatus());

        $this->nextMapVote->reset();
        $this->assertEmpty($this->nextMapVote->getCurrentVote());
    }

    public function testVotePassedAfterTimeout()
    {
        $this->mockPlayerStorage->method('getOnline')->willReturn(['test1', 'test2', 'test3', 'test4', 'test5', 'test6']);

        $player = $this->getPlayer('test', false);
        $this->nextMapVote->start($player, []);

        // 3 person out of 6 votes  and one votes no. Can't decide.
        $this->nextMapVote->castYes('test1');
        $this->nextMapVote->castYes('test2');
        $this->nextMapVote->castNo('test3');
        $this->nextMapVote->update(time());
        $this->assertEquals(Vote::STATUS_RUNNING, $this->nextMapVote->getCurrentVote()->getStatus());

        $this->nextMapVote->update(time()+40);
        $this->assertEquals(Vote::STATUS_PASSED, $this->nextMapVote->getCurrentVote()->getStatus());
    }

    public function testVoteFailedAfterTimeout()
    {
        $this->mockPlayerStorage->method('getOnline')->willReturn(['test1', 'test2', 'test3', 'test4', 'test5', 'test6']);

        $player = $this->getPlayer('test', false);
        $this->nextMapVote->start($player, []);

        // 3 person out of 6 votes  and one votes no. Can't decide.
        $this->nextMapVote->castNo('test1');
        $this->nextMapVote->castNo('test2');
        $this->nextMapVote->castYes('test3');
        $this->nextMapVote->update();
        $this->assertEquals(Vote::STATUS_RUNNING, $this->nextMapVote->getCurrentVote()->getStatus());

        $this->nextMapVote->update(time()+40);
        $this->assertEquals(Vote::STATUS_FAILED, $this->nextMapVote->getCurrentVote()->getStatus());
    }

    public function testUpdateWithoutVoteStarted()
    {
        $this->nextMapVote->update();
    }

    public function testCancelVote()
    {
        $this->mockPlayerStorage->method("getOnline")->willReturn([]);

        $this->nextMapVote->cancel();

        $player = $this->getPlayer('test', false);
        $this->nextMapVote->start($player, []);

        $this->nextMapVote->update();
        $this->assertEquals(Vote::STATUS_RUNNING, $this->nextMapVote->getCurrentVote()->getStatus());

        $this->nextMapVote->cancel();
        $this->assertEquals(Vote::STATUS_CANCEL, $this->nextMapVote->getCurrentVote()->getStatus());
    }

    public function testGetters()
    {
        $this->assertNotEmpty($this->nextMapVote->getCode());
        $this->assertNotEmpty($this->nextMapVote->getReplacementTypes());
        $this->assertNotEmpty($this->nextMapVote->getQuestion());
    }
}
