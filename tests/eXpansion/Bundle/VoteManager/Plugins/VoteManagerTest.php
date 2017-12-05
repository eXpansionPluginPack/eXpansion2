<?php
/**
 * File Test.php
 */

namespace Tests\eXpansion\Bundle\VoteManager\Plugins;

use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\UpdateVoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Plugins\Gui\Widget\VoteWidgetFactory;
use eXpansion\Bundle\VoteManager\Plugins\VoteManager;
use eXpansion\Bundle\VoteManager\Plugins\Votes\AbstractVotePlugin;
use eXpansion\Bundle\VoteManager\Services\VoteService;
use eXpansion\Bundle\VoteManager\Structures\Vote;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class VoteManagerTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockVoteWidgetFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockUpdateVoteWidgetFactory;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayers;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockVoteService;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockCurrentVote;

    /** @var VoteManager */
    protected $voteManager;

    protected function setUp()
    {
        parent::setUp();

        $this->mockVoteWidgetFactory = $this->getMockBuilder(VoteWidgetFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockUpdateVoteWidgetFactory = $this->getMockBuilder(UpdateVoteWidgetFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockPlayers = $this->getMockBuilder(Group::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockVoteService = $this->getMockBuilder(VoteService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockCurrentVote = $this->getMockBuilder(AbstractVotePlugin::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockVoteService->method('getCurrentVote')->willReturn($this->mockCurrentVote);

        $this->voteManager = new VoteManager(
            $this->mockVoteWidgetFactory,
            $this->mockUpdateVoteWidgetFactory,
            $this->mockPlayers,
            $this->mockVoteService
        );
    }

    public function testNewVoteFromDedicated()
    {
        $player = $this->getPlayer('test', false);

        $this->mockVoteService
            ->expects($this->once())
            ->method('startVote')
            ->with($player, 'test', ['value' => 'toto']);

        $this->voteManager->onVoteNew($player, 'test', 'toto');
    }

    public function testnewExpVote()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'toto');

        $this->mockCurrentVote->method('getQuestion')->willReturn('My question of test');

        $this->mockVoteWidgetFactory
            ->expects($this->once())
            ->method('setMessage')
            ->with('My question of test');
        $this->mockVoteWidgetFactory
            ->expects($this->once())
            ->method('create')
            ->with($this->mockPlayers)
            ->willReturn(null);
        $this->mockUpdateVoteWidgetFactory
            ->expects($this->once())
            ->method('create')
            ->with($this->mockPlayers)
            ->willReturn(null);

        $this->voteManager->onVoteNew($player, 'expTest', $vote);
    }

    public function testVoteCancel()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'toto');

        $this->mockVoteWidgetFactory
            ->expects($this->once())
            ->method('destroy')
            ->with($this->mockPlayers)
            ->willReturn(null);
        $this->mockUpdateVoteWidgetFactory
            ->expects($this->once())
            ->method('destroy')
            ->with($this->mockPlayers)
            ->willReturn(null);

        $this->voteManager->onVoteCancelled($player, 'expTest', $vote);
    }

    public function testVotePassed()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'toto');

        $this->mockVoteWidgetFactory
            ->expects($this->once())
            ->method('destroy')
            ->with($this->mockPlayers)
            ->willReturn(null);
        $this->mockUpdateVoteWidgetFactory
            ->expects($this->once())
            ->method('destroy')
            ->with($this->mockPlayers)
            ->willReturn(null);

        $this->voteManager->onVotePassed($player, 'expTest', $vote);
    }

    public function testVoteFailed()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'toto');

        $this->mockVoteWidgetFactory
            ->expects($this->once())
            ->method('destroy')
            ->with($this->mockPlayers)
            ->willReturn(null);
        $this->mockUpdateVoteWidgetFactory
            ->expects($this->once())
            ->method('destroy')
            ->with($this->mockPlayers)
            ->willReturn(null);

        $this->voteManager->onVoteFailed($player, 'expTest', $vote);
    }

    public function testOnEverySecond()
    {
        $this->mockVoteWidgetFactory
            ->expects($this->never())
            ->method('update')
            ->with($this->mockPlayers)
            ->willReturn(null);
        $this->mockUpdateVoteWidgetFactory
            ->expects($this->once())
            ->method('update')
            ->with($this->mockPlayers)
            ->willReturn(null);
        $this->mockVoteService
            ->expects($this->once())
            ->method('update')
            ->with();

        $this->voteManager->onEverySecond();
    }

    public function testEmpty()
    {
        $this->voteManager->onPreLoop();
        $this->voteManager->onPostLoop();
    }
}
