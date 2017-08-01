<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 22:18
 */

namespace Tests\eXpansion\Framework\Core\Helpers\Data;

use eXpansion\Framework\Core\Exceptions\Data\Filter\InvalidFilterTypeException;
use eXpansion\Framework\Core\Helpers\Data\ArrayFilter;
use eXpansion\Framework\Core\Model\Data\FilterInterface;
use Tests\eXpansion\Framework\Core\TestCore;

class ArrayFilterTest extends TestCore
{
    public function testNoFilter()
    {
        $result = $this->getResults([]);
        $this->assertEquals($this->getTestData(), $result);
    }

    public function testFilterSimple()
    {
        $results = $this->getResults(['name' => [FilterInterface::FILTER_TYPE_EQ, 'test name - 1']]);
        $this->assertCount(1, $results);

        $results = $this->getResults(['name' => [FilterInterface::FILTER_TYPE_EQ, 'name - 1']]);
        $this->assertCount(0, $results);

        $results = $this->getResults(['name' => [FilterInterface::FILTER_TYPE_LIKE, 'name - 1']]);
        $this->assertCount(1, $results);

        $results = $this->getResults(['name' => [FilterInterface::FILTER_TYPE_LIKE, 'diff']]);
        $this->assertCount(2, $results);

        $results = $this->getResults(['name' => [FilterInterface::FILTER_TYPE_NEQ, 'test name - 1']]);
        $this->assertCount(3, $results);
    }

    public function testDoubleFilter()
    {
        $results = $this->getResults(
            [
                'name' => [FilterInterface::FILTER_TYPE_EQ, 'test name - 1'],
                'author' => [FilterInterface::FILTER_TYPE_EQ, 'test name - 1'],
            ]
        );
        $this->assertCount(0, $results);

        $results = $this->getResults(
            [
                'name' => [FilterInterface::FILTER_TYPE_EQ, 'test name - 1'],
                'author' => [FilterInterface::FILTER_TYPE_EQ, 'test author - 1'],
            ]
        );
        $this->assertCount(1, $results);
    }

    public function testOrLogic()
    {
        $results = $this->getResults(
            [
                'name' => [FilterInterface::FILTER_TYPE_EQ, 'test name - 1'],
                'author' => [FilterInterface::FILTER_TYPE_EQ, 'test name - 1'],
            ],
            FilterInterface::FILTER_LOGIC_OR
        );
        $this->assertCount(1, $results);
    }

    public function testInalidFilter()
    {
        $this->expectException(InvalidFilterTypeException::class);

        $this->getResults(
            [
                'name' => ['yop yop filter', 'test name - 1'],
            ]
        );
    }

    protected function getTestData()
    {
        return [
            [
                'name'   => 'test name - 1',
                'author' => 'test author - 1',
            ],
            [
                'name'   => 'test name - 2',
                'author' => 'test author - 2"',
            ],
            [
                'name'   => 'test name - 3 diff',
                'author' => 'test author - 3 diff',
            ],
            [
                'name'   => 'test name - 4 diff',
                'author' => 'test author - 4 diff',
            ],
        ];
    }

    protected function getResults($filter, $logic = FilterInterface::FILTER_LOGIC_AND)
    {
        $filterHelper = $this->getArrayFilterHelper();
        return $filterHelper->filterData($this->getTestData(), $filter, $logic);
    }

    /**
     * @return ArrayFilter
     */
    protected function getArrayFilterHelper()
    {
        return $this->container->get('expansion.helper.data.array_filter');
    }
}
