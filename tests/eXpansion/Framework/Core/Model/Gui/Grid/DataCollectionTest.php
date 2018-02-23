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
    /**
     * Test that the data collection factories return proper objects.
     */
    public function testFactories()
    {
        $this->assertInstanceOf(DataCollectionInterface::class, $this->getObjectDataCollectionFactory()->create([]));
        $this->assertInstanceOf(DataCollectionInterface::class, $this->getArrayDataCollectionFactory()->create([]));
    }

    /**
     * Test that fetching data from the data collections is possible.
     */
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

    /**
     * Test switching to last page.
     */
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

    /**
     * Test applying filters
     */
    public function testFilters()
    {
        $data = $this->getData(10);
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);
        $dataCollection->setPageSize(20);
        $dataCollection->setFiltersAndSort(['data' => [FilterInterface::FILTER_TYPE_EQ, '1']], '', '');

        $this->assertEquals([['data' => 1]], $dataCollection->getData(1));

        return $dataCollection;
    }

    /**
     * Test reseting filters.
     */
    public function testFilterReset()
    {
        $dataCollection = $this->testFilters();

        $dataCollection->setFiltersAndSort(['data' => [FilterInterface::FILTER_TYPE_EQ, '2']], '', '');
        $this->assertEquals([['data' => 2]], $dataCollection->getData(1));
    }

    /**
     * Test reseting filters.
     */
    public function testReset()
    {
        $dataCollection = $this->testFilters();
        $dataCollection->reset();

        $this->assertCount(10, $dataCollection->getData(1));
    }

    /**
     * Test that sorting of strings works well.
     */
    public function testSortingOfStringValues()
    {
        $data = [['data' => 'daaa'], ['data' => 'abc'], ['data' => 'aaa'], ['data' => 'ccc']];
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);

        $dataCollection->setFiltersAndSort([], 'data', 'ASC');
        $this->assertEquals(
            [['data' => 'aaa'], ['data' => 'abc'], ['data' => 'ccc'], ['data' => 'daaa']],
            $dataCollection->getData(1)
        );
    }

    /**
     * Test that sorting of integer values works well
     */
    public function testSortingOfIntegeralues()
    {
        $data = [['data' => '12'], ['data' => 5], ['data' => '4'], ['data' => 1]];
        $dataCollection = $this->getArrayDataCollectionFactory()->create($data);

        $dataCollection->setFiltersAndSort([], 'data', 'DESC');
        $this->assertEquals(
            [['data' => '12'], ['data' => 5], ['data' => '4'], ['data' => 1]],
            $dataCollection->getData(1)
        );
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
        return $this->container->get('expansion.gui.gridbuilder.datacollection.object');
    }

    /**
     * @return DataCollectionFactory
     */
    protected function getArrayDataCollectionFactory()
    {
        return $this->container->get('expansion.gui.gridbuilder.datacollection.array');
    }
}
