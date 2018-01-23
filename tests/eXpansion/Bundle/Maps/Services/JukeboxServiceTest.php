<?php
/**
 * Created by PhpStorm.
 * User: Petri
 * Date: 23.1.2018
 * Time: 16.36
 */

namespace Tests\eXpansion\Bundle\Maps\Services;


use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Bundle\Maps\Structure\JukeboxMap;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Tests\eXpansion\Framework\Core\TestHelpers\MapDataTrait;
use Tests\eXpansion\Framework\Core\TestHelpers\PlayerDataTrait;

class JukeboxServiceTest extends \PHPUnit_Framework_TestCase
{


    use PlayerDataTrait;
    use MapDataTrait;


    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $playerStorage;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    private $adminGroups;

    /** @var JukeboxService */
    private $jukeboxService;

    /** @var Player */
    private $expPlayer1;

    /** @var Player */
    private $expPlayer2;


    public function setUp()
    {
        $this->expPlayer1 = $this->getPlayer("toto1", false);
        $this->expPlayer2 = $this->getPlayer("toto2", false);

        $this->playerStorage = $this->getMockBuilder(PlayerStorage::class)->disableOriginalConstructor()->getMock();

        $this->playerStorage->expects($this->any())
            ->method('getPlayerInfo')
            ->with($this->anything())
            ->will($this->returnCallback(
                function ($entityName) {
                    if ($entityName === 'toto1') {
                        return $this->getPlayer("toto1", false);
                    }

                    if ($entityName === 'toto2') {
                        return $this->getPlayer("toto2", false);
                    }
                }
            ));


        $this->adminGroups = $this->getMockBuilder(AdminGroups::class)->disableOriginalConstructor()->getMock();

        $this->jukeboxService = new JukeboxService($this->playerStorage, $this->adminGroups);

    }

    public function testEmptyMapQueue()
    {
        $this->assertEmpty($this->jukeboxService->getMapQueue());
    }

    public function testAddMapQueue()
    {
        $player = $this->getPlayer("toto1", false);

        $map = $this->getAMap("test123");

        $jukeboxMap = new JukeboxMap($map, $player);
        $this->jukeboxService->addMap($map, $player->getLogin(), false);
        $this->assertEquals([$jukeboxMap], $this->jukeboxService->getMapQueue());

    }

    public function testAddMapFirst()
    {
        $player = $this->getPlayer("toto1", false);

        $map1 = $this->getAMap("test-1");
        $map2 = $this->getAMap("test-2");
        $map3 = $this->getAMap("test-3");


        $jukeboxMap1 = new JukeboxMap($map1, $player);
        $jukeboxMap2 = new JukeboxMap($map2, $player);
        $jukeboxMap3 = new JukeboxMap($map3, $player);

        $this->jukeboxService->addMap($map1, $player->getLogin(), true);
        $this->assertEquals([$jukeboxMap1], $this->jukeboxService->getMapQueue());

        $this->jukeboxService->addMapFirst($map2, $player->getLogin(), true);
        $this->assertEquals([$jukeboxMap2, $jukeboxMap1], $this->jukeboxService->getMapQueue());

        $this->jukeboxService->addMap($map3, $player->getLogin(), true);
        $this->assertEquals([$jukeboxMap2, $jukeboxMap1, $jukeboxMap3], $this->jukeboxService->getMapQueue());

        $this->jukeboxService->clearMapQueue();
    }

    public function testAddMapLast()
    {
        $player = $this->getPlayer("toto1", false);
        $map1 = $this->getAMap("test-1");
        $map2 = $this->getAMap("test-2");

        $jukeboxMap1 = new JukeboxMap($map1, $player);
        $jukeboxMap2 = new JukeboxMap($map2, $player);

        $this->jukeboxService->addMap($map1, $player->getLogin(), true);
        $this->assertEquals([$jukeboxMap1], $this->jukeboxService->getMapQueue());

        $this->jukeboxService->addMapLast($map2, $player->getLogin(), true);
        $this->assertEquals([$jukeboxMap1, $jukeboxMap2], $this->jukeboxService->getMapQueue());

        $this->jukeboxService->clearMapQueue();
    }


    public function testAddMultipleMapsAsUser()
    {
        $player = $this->getPlayer("toto1", false);
        $map1 = $this->getAMap("test-1");
        $map2 = $this->getAMap("test-2");

        $jukeboxMap1 = new JukeboxMap($map1, $player);

        $this->jukeboxService->addMap($map1, $player->getLogin(), false);
        $this->assertEquals([$jukeboxMap1], $this->jukeboxService->getMapQueue());

        $this->jukeboxService->addMap($map2, $player->getLogin(), false);
        $this->assertEquals([$jukeboxMap1], $this->jukeboxService->getMapQueue());
        $this->jukeboxService->clearMapQueue();

    }


    public function testRemoveMap()
    {
        $player1 = $this->getPlayer("toto1", false);
        $player2 = $this->getPlayer("toto2", false);

        $map1 = $this->getAMap("test-1");
        $map2 = $this->getAMap("test-2");
        $map3 = $this->getAMap("test-3");

        $jukeboxMap1 = new JukeboxMap($map1, $player1);
        $jukeboxMap2 = new JukeboxMap($map2, $player2);

        $this->jukeboxService->addMap($map1, $player1->getLogin(), true);

        $this->jukeboxService->addMap($map2, $player2->getLogin(), true);
        $this->assertEquals([$jukeboxMap1, $jukeboxMap2], $this->jukeboxService->getMapQueue());

        // test non-admin try remove map which is not own
        $this->assertFalse($this->jukeboxService->removeMap($map2, $player1->getLogin()));
        $this->assertEquals([$jukeboxMap1, $jukeboxMap2], $this->jukeboxService->getMapQueue());

        // player2 remove own map
        $this->assertTrue($this->jukeboxService->removeMap($map2, $player2->getLogin()));
        $this->assertEquals([$jukeboxMap1], $this->jukeboxService->getMapQueue());

        // player1 removes map that doesn't exist
        $this->assertFalse($this->jukeboxService->removeMap($map3, $player1->getLogin()));
        $this->assertEquals([$jukeboxMap1], $this->jukeboxService->getMapQueue());

        // player1 remove own map
        $this->assertTrue($this->jukeboxService->removeMap($map1, $player1->getLogin()));
        $this->assertEquals([], $this->jukeboxService->getMapQueue());

    }





}