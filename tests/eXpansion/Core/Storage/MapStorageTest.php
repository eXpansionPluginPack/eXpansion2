<?php


namespace Tests\eXpansion\Core\Storage;


use eXpansion\Core\Storage\MapStorage;
use Tests\eXpansion\Core\TestCore;
use Tests\eXpansion\Core\TestHelpers\MapDataTrait;

class MapStorageTest extends TestCore
{
    use MapDataTrait;

    public function testAddMapGetMap()
    {
        $map = $this->getAMap('test-1');
        $mapStorage = $this->getMapStorage();

        $mapStorage->addMap($map);
        $this->assertEquals($map, $mapStorage->getMap('test-1'));
    }

    public function testGetMaps()
    {
        $maps = $this->getMaps(10, 0);
        $mapsByIndex = array_values($maps);
        $mapStorage = $this->getMapStorage();

        foreach ($maps as $map) {
            $mapStorage->addMap($map);
        }

        $this->assertSame($maps, $mapStorage->getMaps());
        $this->assertSame($mapsByIndex[0], $mapStorage->getMapByIndex(0));
        $this->assertSame($mapsByIndex[2], $mapStorage->getMapByIndex('2'));
        $this->assertFalse($mapStorage->getMapByIndex(20));
    }

    public function testCurrentNextMap()
    {
        $mapA = $this->getAMap('test-1');
        $mapB = $this->getAMap('test-2');

        $mapStorage = $this->getMapStorage();
        $mapStorage->addMap($mapA);
        $mapStorage->addMap($mapB);

        $mapStorage->setCurrentMap($mapA);
        $mapStorage->setNextMap($mapB);

        $this->assertEquals($mapA, $mapStorage->getCurrentMap());
        $this->assertEquals($mapB, $mapStorage->getNextMap());
    }

    public function testResetMapData()
    {
        $this->testGetMaps();
        $mapStorage = $this->getMapStorage();

        $mapStorage->resetMapData();
        $this->assertEmpty($mapStorage->getMaps());
    }

    /**
     *
     * @return MapStorage
     */
    protected function getMapStorage()
    {
        return $this->container->get('expansion.core.storage.map');
    }
}
