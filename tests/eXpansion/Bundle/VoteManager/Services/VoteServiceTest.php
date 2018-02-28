<?php
/**
 * File VoteServiceTest.php
 */

namespace Tests\eXpansion\Bundle\VoteManager\Services;

use eXpansion\Bundle\VoteManager\Plugins\Votes\AbstractVotePlugin;
use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Connection;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class VoteServiceTest extends TestCore
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConsole;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockDispatcher;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockVotePlugin;

    /** @var VoteService */
    protected $voteService;


    protected function setUp()
    {
        parent::setUp();

        $this->mockConsole = $this->getMockBuilder(Console::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockChatNotification = $this->getMockBuilder(ChatNotification::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockDispatcher = $this->getMockBuilder(Dispatcher::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockVotePlugin = $this->getMockBuilder(AbstractVotePlugin::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockVotePlugin->method('getCode')->willReturn('eXpTestCode');
        $this->mockVotePlugin->method('getReplacementTypes')->willReturn(['TestCode']);

        $this->voteService = new VoteService(
            $this->mockConsole,
            $this->mockConnectionFactory,
            $this->mockChatNotification,
            $this->mockDispatcher,
            [$this->mockVotePlugin]
        );
    }

    public function testStartVote()
    {
        $player = $this->getPlayer('test', false);

        $this->mockVotePlugin->method('getCurrentVote')->willReturn(new Vote($player, 'eXpTestCode'));

        $this->mockVotePlugin->expects($this->once())->method('start')->with($player, ['toto']);
        $this->mockConnection->expects($this->once())->method('cancelVote');
        $this->mockDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with('votemanager.votenew', new \PHPUnit_Framework_Constraint_IsAnything());

        $this->voteService->startVote($player, 'TestCode', ['toto']);
    }

    public function testStartMultipleVotes()
    {
        $this->mockChatNotification
            ->expects($this->once())
            ->method('sendMessage')
            ->with(new \PHPUnit_Framework_Constraint_StringContains('in_progress'));

        $this->testStartVote();

        $player = $this->getPlayer('test', false);
        $this->voteService->startVote($player, 'TestCode', ['toto']);
    }

    public function testUnknownVotePlugin()
    {
        $this->mockChatNotification
            ->expects($this->never())
            ->method('sendMessage')
            ->with(new \PHPUnit_Framework_Constraint_StringContains('error'));

        $player = $this->getPlayer('test', false);
        $this->voteService->startVote($player, 'TestCodeUnknown', ['toto']);
    }
    
    public function testCancelVote()
    {
        $this->mockVotePlugin->expects($this->once())->method('cancel');

        $this->voteService->cancel();
        $this->testStartVote();
        $this->voteService->cancel();
    }

    public function testPassedVote()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'eXpTestCode');
        $this->mockVotePlugin->method('getCurrentVote')->willReturn($vote);
        $this->voteService->startVote($player, 'TestCode', ['toto']);

        $this->mockVotePlugin->expects($this->once())->method('update');
        $this->mockDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with('votemanager.votepassed', new \PHPUnit_Framework_Constraint_IsAnything());

        $vote->setStatus(Vote::STATUS_PASSED);
        $this->voteService->update();
    }

    public function testFailedVote()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'eXpTestCode');
        $this->mockVotePlugin->method('getCurrentVote')->willReturn($vote);
        $this->voteService->startVote($player, 'TestCode', ['toto']);

        $this->mockVotePlugin->expects($this->once())->method('update');
        $this->mockDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with('votemanager.votefailed', new \PHPUnit_Framework_Constraint_IsAnything());

        $vote->setStatus(Vote::STATUS_FAILED);
        $this->voteService->update();
    }

    public function testCanceledVote()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'eXpTestCode');
        $this->mockVotePlugin->method('getCurrentVote')->willReturn($vote);
        $this->voteService->startVote($player, 'TestCode', ['toto']);

        $this->mockVotePlugin->expects($this->once())->method('update');
        $this->mockDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with('votemanager.votecancelled', new \PHPUnit_Framework_Constraint_IsAnything());

        $vote->setStatus(Vote::STATUS_CANCEL);
        $this->voteService->update();
    }

    public function testCastVoteYes()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'eXpTestCode');
        $this->mockVotePlugin->method('getCurrentVote')->willReturn($vote);
        $this->voteService->startVote($player, 'TestCode', ['toto']);

        $this->mockVotePlugin->expects($this->once())->method('castYes')->with('test2');

        $this->voteService->castVote('test2', Vote::VOTE_YES);
    }

    public function testCastVoteNo()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'eXpTestCode');
        $this->mockVotePlugin->method('getCurrentVote')->willReturn($vote);
        $this->voteService->startVote($player, 'TestCode', ['toto']);

        $this->mockVotePlugin->expects($this->once())->method('castNo')->with('test2');

        $this->voteService->castVote('test2', Vote::VOTE_NO);
    }

    public function testReset()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'eXpTestCode');
        $this->mockVotePlugin->method('getCurrentVote')->willReturn($vote);
        $this->voteService->startVote($player, 'TestCode', ['toto']);

        $this->mockVotePlugin->expects($this->once())->method('reset');

        $this->voteService->reset();
    }
}
