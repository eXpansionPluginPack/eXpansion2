<?php
/**
 * File Test.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Bundle\VoteManager\Services\VoteFactories;

use eXpansion\Bundle\VoteManager\Services\VoteFactories\NextMap;
use eXpansion\Bundle\VoteManager\Structures\NextMapVote;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use Maniaplanet\DedicatedServer\Connection;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class NextMapTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConnection;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockChatNotification;

    /** @var NextMap */
    protected $nextMap;

    protected function setUp()
    {
        parent::setUp();

        $this->mockConnection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->mockChatNotification = $this->getMockBuilder(ChatNotification::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->nextMap = new NextMap(
            30,
            0.57,
            $this->mockConnection,
            $this->mockChatNotification
        );
    }


    public function testCreate()
    {
        $player = $this->getPlayer('test', false);
        $vote = $this->nextMap->create($player);

        $this->assertInstanceOf(NextMapVote::class, $vote);
        $this->assertEquals($player, $vote->getPlayer());
    }

    public function testGetters()
    {
        $this->assertNotEmpty($this->nextMap->getVoteCode());
        $this->assertNotEmpty($this->nextMap->getReplacementTypes());
    }
}
