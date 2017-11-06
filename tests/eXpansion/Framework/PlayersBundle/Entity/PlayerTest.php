<?php
/**
 * File PlayerTest.php
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 */

namespace Tests\eXpansion\Framework\PlayersBundle\Entity;

use eXpansion\Framework\PlayersBundle\Entity\Player;

class PlayerTest extends \PHPUnit_Framework_TestCase
{
    public function testGettersSetters()
    {
        $now = new \DateTime('now');

        $player = new Player();
        $player->setLogin('toto-1');
        $player->setNickname('toto-1 - nick');
        $player->setNicknameStripped('toto-1 - nicks');
        $player->setLastOnline($now);
        $player->setOnlineTime(10);
        $player->setWins(15);
        $player->setPath('World/Europe/France');

        $this->assertEquals('toto-1', $player->getLogin());
        $this->assertEquals('toto-1 - nick', $player->getNickname());
        $this->assertEquals('toto-1 - nicks', $player->getNicknameStripped());
        $this->assertEquals($now, $player->getLastOnline());
        $this->assertEquals(15, $player->getWins());
        $this->assertEquals(10, $player->getOnlineTime());
        $this->assertEquals(null, $player->getId());
        $this->assertEquals('World/Europe/France', $player->getPath());
    }
}
