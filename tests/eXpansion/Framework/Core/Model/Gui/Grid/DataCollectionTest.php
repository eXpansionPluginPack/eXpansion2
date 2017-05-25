<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 25/05/2017
 * Time: 10:18
 */

namespace Tests\eXpansion\Framework\Core\Model\Gui\Grid;

use eXpansion\Framework\Core\Model\Data\FilterInterface;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollection;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionInterface;
use Tests\eXpansion\Framework\Core\TestCore;

class DataCollectionTest extends TestCore
{
    public function testFactories()
    {
        $this->assertInstanceOf(DataCollectionInterface::class, $this->getObjectDataCollectionFactory()->create([]));
        $this->assertInstanceOf(DataCollectionInterface::class, $this->getArrayDataCollectionFactory()->create([]));
    }

    public function testGetData()
    {
        $data = $this->getData(12);
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);
        $dataCollection->setPageSize(5);

        $this->assertCount(5, $dataCollection->getData(1));
        $this->assertCount(5, $dataCollection->getData(2));
        $this->assertCount(2, $dataCollection->getData(3));
        $this->assertEmpty($dataCollection->getData(4));
    }

    public function testLastPage()
    {
        $data = $this->getData(10);
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);
        $dataCollection->setPageSize(5);
        $this->assertEquals(2, $dataCollection->getLastPageNumber());

        $data = $this->getData(11);
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);
        $dataCollection->setPageSize(5);
        $this->assertEquals(3, $dataCollection->getLastPageNumber());

        $data = $this->getData(14);
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);
        $dataCollection->setPageSize(5);
        $this->assertEquals(3, $dataCollection->getLastPageNumber());

        $data = $this->getData(15);
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);
        $dataCollection->setPageSize(5);
        $this->assertEquals(3, $dataCollection->getLastPageNumber());
    }

    public function testFilters()
    {
        $data = $this->getData(10);
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);
        $dataCollection->setPageSize(20);
        $dataCollection->setFiltersAndSort(['data' => [FilterInterface::FILTER_TYPE_EQ, '1']], '', '');

        $this->assertEquals([['data' => 1]], $dataCollection->getData(1));

        return $dataCollection;
    }

    public function testFilterReset()
    {
        $dataCollection = $this->testFilters();

        $dataCollection->setFiltersAndSort(['data' => [FilterInterface::FILTER_TYPE_EQ, '2']], '', '');
        $this->assertEquals([['data' => 2]], $dataCollection->getData(1));
    }

    public function testReset()
    {
        $dataCollection = $this->testFilters();
        $dataCollection->reset();

        $this->assertCount(10, $dataCollection->getData(1));
    }

    protected function getData($size = 20)
    {
        $data = [];
        for ($i = 0; $i < $size; $i++) {
            $data[] = ['data' => $i];
        }

        return $data;
    }

    /**
     * @return DataCollectionFactory
     */
    protected function getObjectDataCollectionFactory()
    {
        return $this->container->get('expansion.framework.core.model.gui.grid.data_collection_factory.object');
    }

    /**
     * @return DataCollectionFactory
     */
    protected function getArrayDataCollectionFactory()
    {
        return $this->container->get('expansion.framework.core.model.gui.grid.data_collection_factory.array');
    }
}
