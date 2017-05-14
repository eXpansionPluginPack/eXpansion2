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
     * Set filters & sorting to apply to the data.
     *
     * @param array $filters List of filters with the fallowing format :
     *                          ['key_to_filter'=> ['type_of_filter' , 'wordl"]]
     *                       For the possible types of filters check FilterInstance constants.
     *                       Example to find a map or author containing the keyword "hello"
     *                          ['name'=> ['like', 'hello"], 'author_loin'=> ['like', 'hello"]]
     * @param array $sortField Field to sort on
     * @param array $sortOrder Order DESC or ASC.
     *
     * @return $this
     */
    public function setFiltersAndSort($filters, $sortField, $sortOrder);

    /**
     * Set the number of elements to display on each page.
     *
     * @param int $size Size of each page
     *
     * @return mixed
     */
    public function setPageSize($size);

    /**
     * Reset current filters & sorting.
     *
     * @return mixed
     */
    public function reset();
}
