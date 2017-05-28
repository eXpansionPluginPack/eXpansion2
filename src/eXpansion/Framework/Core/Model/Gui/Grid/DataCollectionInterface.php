<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid;

/**
 * Interface DataCollectionInterface
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
interface DataCollectionInterface
{
    /**
     * Get the data that needs to be added on a certain page.
     *
     * @param int $page The page to get the data for.
     *
     * @return array()
     */
    public function getData($page);

    /**
     * Read data on a certain line
     *
     * @param mixed  $lineData
     * @param string $key
     *
     * @return string
     */
    public function getLineData($lineData, $key);

    /**
     * Get the number of the last page.
     *
     * @return int
     */
    public function getLastPageNumber();

    /**
     * Set filters & sorting to apply to the data.
     *
     * @param array $filters List of filters with the fallowing format :
     *                          ['key_to_filter'=> ['type_of_filter' , 'wordl"]]
     *                       For the possible types of filters check FilterInstance constants.
     *                       Example to find a map or author containing the keyword "hello"
     *                          ['name'=> ['like', 'hello"], 'author_loin'=> ['like', 'hello"]]
     * @param string $sortField Field to sort on
     * @param string $sortOrder Order DESC or ASC.
     *
     * @return $this
     */
    public function setFiltersAndSort($filters, $sortField, $sortOrder);

    /**
     * Set the number of elements to display on each page.
     *
     * @param int $size Size of each page
     *
     * @return $this
     */
    public function setPageSize($size);

    /**
     * Reset current filters & sorting.
     *
     * @return $this
     */
    public function reset();
}
