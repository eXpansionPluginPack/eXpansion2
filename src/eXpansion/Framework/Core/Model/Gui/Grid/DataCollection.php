<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid;

use eXpansion\Framework\Core\Model\Data\FilterInterface;


/**
 * Class DataCollection
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class DataCollection implements DataCollectionInterface
{
    /** @var array */
    protected $data;

    /** @var  array|null */
    protected $filteredData;

    /** @var FilterInterface */
    protected $filterHelper;

    /** @var array */
    protected $filters;

    /** @var null|array */
    protected $sort = null;

    /** @var int */
    protected $pageSize;

    /**
     * DataCollection constructor.
     *
     * @param array $data
     * @param FilterInterface $filter
     */
    public function __construct($data, FilterInterface $filter)
    {
        $this->data = $data;
        $this->filterHelper = $filter;
    }

    /**
     * Get the data that needs to be added on a certain page.
     *
     * @param int $page The page to get the data for. Pages starts a 1.
     *
     * @return array
     */
    public function getData($page)
    {
        $this->loadData();
        $start = ($page - 1) * $this->pageSize;

        return array_slice($this->filteredData, $start, $this->pageSize);
    }

    /**
     * Read data on a certain line
     *
     * @param mixed $lineData
     * @param string $key
     *
     * @return string
     */
    public function getLineData($lineData, $key)
    {
        return $this->filterHelper->getFieldValue($lineData, $key);
    }

    /**
     * Get the number of the last page.
     *
     * @return int
     */
    public function getLastPageNumber()
    {
        $this->loadData();
        $count = count($this->filteredData);

        return ceil($count / $this->pageSize);
    }

    /**
     * Set filters & sorting to apply to the data.
     *
     * @param array $filters List of filters with the fallowing format :
     *                          ['key_to_filter'=> ['type_of_filter' , 'wordl"]]
     *                          For the possible types of filters check FilterInstance constants.
     *                          Example to find a map or author containing the keyword "hello"
     *                          ['name'=> ['like', 'hello"], 'author_loin'=> ['like', 'hello"]]
     * @param string $sortField Field to sort on
     * @param string $sortOrder Order DESC or ASC.
     *
     * @return $this
     */
    public function setFiltersAndSort($filters, $sortField = null, $sortOrder = "ASC")
    {
        $this->reset();

        $this->filters = $filters;
        if ($sortField && $sortOrder) {
            $this->sort = [$sortField, $sortOrder];
        }

        return $this;
    }

    /**
     * sets new data to line
     *
     * @param $index
     * @param $data
     */
    public function setDataByIndex($line, $data)
    {
        $this->data[$line] = $data;
        $this->filteredData = null;
    }

    /**
     * Set the number of elements to display on each page.
     *
     * @param int $size Size of each page
     *
     * @return $this
     */
    public function setPageSize($size)
    {
        $this->pageSize = $size;

        return $this;
    }

    /**
     * Reset current filters & sorting.
     *
     * @return $this
     */
    public function reset()
    {
        $this->filteredData = null;
        $this->filters = [];
        $this->sort = null;

        return $this;
    }

    /**
     * Filter & sort the data.
     */
    protected function loadData()
    {
        if (is_null($this->filteredData)) {
            $this->filteredData = $this->filterHelper->filterData(
                $this->data,
                $this->filters,
                FilterInterface::FILTER_LOGIC_OR
            );

            if (!is_null($this->sort)) {
                $sort = $this->sort;
                uasort($this->filteredData, function ($a, $b) use ($sort) {
                    $comp = (strcmp($a[$sort[0]], $b[$sort[0]]));
                    if ($sort[1] == "DESC") {
                        return -1 * $comp;
                    } else {
                        return $comp;
                    }
                });
            }
        }
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }


    /**
     * @return array
     */
    public function getAll()
    {
        return $this->data;
    }

}
