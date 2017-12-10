<?php
/**
 * File RestartMapVoteTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\VoteManager\Plugins\Votes;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\VoteManager\Plugins\Votes\RestartMapVote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\MapStorage;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Structures\Map;
use Tests\eXpansion\Framework\Core\TestHelpers\MapDataTrait;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class RestartMapVoteTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;
    use MapDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockPlayerStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockJukebox;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockMapStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var RestartMapVote */
    protected $restartMapVote;

    /** @var Map */
    private $tempMap;

    protected function setUp()
    {
        parent::setUp();

        $this->mockPlayerStorage = $this->getMockBuilder(PlayerStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockJukebox = $this->getMockBuilder(JukeboxService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockMapStorage = $this->getMockBuilder(MapStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockChatNotification = $this->getMockBuilder(ChatNotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->restartMapVote = new RestartMapVote(
            $this->mockPlayerStorage,
            $this->mockJukebox,
            $this->mockMapStorage,
            $this->mockChatNotification,
            30,
            0.57
        );
    }

    public function testPassed()
    {
        $this->map = $this->getAMap('test');

        $this->mockJukebox->expects($this->once())->method('addMap')->with($this->map);

        $this->mockPlayerStorage->method('getOnline')->willReturn(['test1', 'test2', 'test3', 'test4']);

        $player = $this->getPlayer('test', false);


        $this->mockMapStorage
            ->expects($this->once())
            ->method('getCurrentMap')
            ->willReturn($this->map);

        $this->restartMapVote->start($player, []);

        // 3 person out of 4 votes yes pass vote before timeout.
        $this->restartMapVote->castYes('test1');
        $this->restartMapVote->castYes('test2');
        $this->restartMapVote->castYes('test3');
        $this->restartMapVote->update(time());
    }

    public function testFailed()
    {
        $this->mockPlayerStorage->method('getOnline')->willReturn(['test1', 'test2', 'test3', 'test4']);

        $player = $this->getPlayer('test', false);
        $this->restartMapVote->start($player, []);

        // 3 person out of 4 votes yes pass vote before timeout.
        $this->restartMapVote->castNo('test1');
        $this->restartMapVote->update(time() + 40);
    }


    public function testGetters()
    {
        $this->assertNotEmpty($this->restartMapVote->getCode());
        $this->assertNotEmpty($this->restartMapVote->getReplacementTypes());
        $this->assertNotEmpty($this->restartMapVote->getQuestion());
    }
}
