<?php


namespace Tests\eXpansion\Framework\GameManiaplanet\DataProviders;

use eXpansion\Framework\GameManiaplanet\DataProviders\MapListDataProvider;
use eXpansion\Framework\Core\Storage\MapStorage;
use Maniaplanet\DedicatedServer\Xmlrpc\IndexOutOfBoundException;
use Tests\eXpansion\Framework\Core\TestCore;
use Tests\eXpansion\Framework\Core\TestHelpers\MapDataTrait;

class MapDataProviderTest extends TestCore
{
    use MapDataTrait;

    protected function setUp()
    {
        parent::setUp();
    }

    protected function getParamsArray($params)
    {
        $paramsArray = [];
        foreach ($params as $param) {
            $paramsArray[] = [$param];
        }

        return $paramsArray;
    }

    public function testUpdateMapList()
    {
        $mapPack1 = $this->getMaps(500, 0);
        $mapPack2 = $this->getMaps(500, 500);
        $allMaps = array_merge($mapPack1, $mapPack2);
        $maps = array_values($allMaps);

        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.service.dedicated_connection');
        $connectionMock->expects($this->exactly(3))
            ->method('getMapList')
            ->withConsecutive([500, 0], [500, 500])
            ->willReturn($mapPack1, $mapPack2);
        $connectionMock
            ->expects($this->at(2))
            ->method('getMapList')
            ->willThrowException(new IndexOutOfBoundException('test'));
        $connectionMock
            ->method('getCurrentMapInfo')
            ->willReturn($maps[0]);
        $connectionMock
            ->method('getNextMapInfo')
            ->willReturn($maps[1]);

        $mapStorageMock = $this->createMock(MapStorage::class);
        $mapStorageMock->expects($this->exactly(count($allMaps)))
            ->method('addMap');
         $this->container->set(MapStorage::class, $mapStorageMock);

        $this->container->get('expansion.framework.core.data_providers.map_data_provider');
    }

    public function testMapListModified()
    {
        $mapPack1 = $this->getMaps(10, 0);
        $uids1 = array_keys($mapPack1);
        $mapPack2 = $this->getMaps(10, 500);
        $uids2 = array_keys($mapPack2);

        /** @var \PHPUnit_Framework_MockObject_MockObject $connectionMock */
        $connectionMock = $this->container->get('expansion.service.dedicated_connection');
        $connectionMock->expects($this->exactly(2))
            ->method('getMapList')
            ->withConsecutive([500, 0], [500, 0])
            ->willReturn($mapPack1, $mapPack2);
        $connectionMock
            ->method('getCurrentMapInfo')
            ->willReturn($mapPack1[$uids1[0]]);
        $connectionMock
            ->method('getNextMapInfo')
            ->willReturn($mapPack1[$uids1[1]]);

        $mapStorageMock = $this->createMock(MapStorage::class);
        $mapStorageMock->method('getMaps')->willReturn($mapPack1);
        $mapStorageMock->method('getCurrentMap')->willReturn($mapPack1[$uids1[0]]);
        $mapStorageMock->method('getNextMap')->willReturn($mapPack1[$uids1[1]]);
        $mapStorageMock->expects($this->exactly(20))->method('addMap');
        $mapStorageMock->method('getMapByIndex')->willReturn($mapPack2[$uids2[0]], $mapPack2[$uids2[1]]);
        $mapStorageMock->expects($this->exactly(2))->method('setCurrentMap');
        $mapStorageMock->expects($this->exactly(2))->method('setNextMap');

        $this->container->set(MapStorage::class, $mapStorageMock);

        /** @var MapDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.framework.core.data_providers.map_data_provider');
        $dataProvider->onMapListModified($uids2[0], $uids2[1], true);
    }
}
