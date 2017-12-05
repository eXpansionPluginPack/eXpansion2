<?php
/**
 * File VoteTest.php
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 */

namespace Tests\eXpansion\Bundle\VoteManager\Structures;

use eXpansion\Bundle\VoteManager\Structures\Vote;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class VoteTest extends \PHPUnit_Framework_TestCase
{
    use PlayerDataTrait;

    public function testGetters()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'test', ['test']);

        $this->assertEquals($player, $vote->getPlayer());
        $this->assertEquals('test', $vote->getType());
        $this->assertEquals(['test'], $vote->getParams());
        $this->assertEquals(Vote::STATUS_RUNNING, $vote->getStatus());
        $this->assertNotEmpty($vote->getStartTime());
    }

    public function testCastVote()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'test', ['test']);

        $vote->castYes('test1');
        $vote->castYes('test2');
        $vote->castYes('test3');
        $vote->castNo('test4');
        $vote->castNo('test5');

        $this->assertEquals(3, $vote->getYes());
        $this->assertEquals(2, $vote->getNo());
    }

    public function testStatusChange()
    {
        $player = $this->getPlayer('test', false);
        $vote = new Vote($player, 'test', ['test']);

        $vote->setStatus(Vote::STATUS_CANCEL);
        $this->assertEquals(Vote::STATUS_CANCEL, $vote->getStatus());

    }
}
