<?php
/**
 * File RestartMapVoteTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\VoteManager\Structures;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\VoteManager\Structures\RestartMapVote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\MapStorage;
use Tests\eXpansion\Framework\Core\TestHelpers\MapDataTrait;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class RestartMapVoteTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;
    use MapDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockJukebox;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockMapStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var RestartMapVote */
    protected $vote;

    protected function setUp()
    {
        parent::setUp();

        $this->mockJukebox = $this->getMockBuilder(JukeboxService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockMapStorage = $this->getMockBuilder(MapStorage::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockChatNotification = $this->getMockBuilder(ChatNotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->vote = new RestartMapVote(
            $this->getPlayer('starter', false),
            'test',
            30,
            0.57,
            $this->mockJukebox,
            $this->mockMapStorage,
            $this->mockChatNotification
        );
    }

    public function testExecutePassed()
    {
        $map = $this->getAMap('test');

        $this->mockMapStorage
            ->expects($this->once())
            ->method('getCurrentMap')
            ->willReturn($map);
        $this->mockJukebox->expects($this->once())->method('addMap')->with($map);
        $this->vote->executeVotePassed();
        $this->vote->executeVoteFailed();
    }

    public function testGetQuestion()
    {
        $this->assertNotEmpty($this->vote->getQuestion());
    }
}
