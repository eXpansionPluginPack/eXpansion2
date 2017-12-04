<?php
/**
 * File Test.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\VoteManager\Services\VoteFactories;

use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\VoteManager\Services\VoteFactories\RestartMap;
use eXpansion\Bundle\VoteManager\Structures\RestartMapVote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Storage\MapStorage;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class RestartMapTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockJukebox;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockMapStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var RestartMap */
    protected $restartMap;

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

        $this->restartMap = new RestartMap(
            30,
            0.57,
            $this->mockJukebox,
            $this->mockMapStorage,
            $this->mockChatNotification
        );
    }


    public function testCreate()
    {
        $player = $this->getPlayer('test', false);
        $vote = $this->restartMap->create($player);

        $this->assertInstanceOf(RestartMapVote::class, $vote);
        $this->assertEquals($player, $vote->getPlayer());
    }

    public function testGetters()
    {
        $this->assertNotEmpty($this->restartMap->getVoteCode());
        $this->assertNotEmpty($this->restartMap->getReplacementTypes());
    }
}
