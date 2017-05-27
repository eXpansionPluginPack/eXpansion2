<?php

namespace eXpansion\Framework\Core\Model\Data;


/**
 * Interface FilterInterface
 *
 * @package eXpansion\Framework\Core\Model\Data;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface FilterInterface
{
    const FILTER_TYPE_EQ = "eq";
    const FILTER_TYPE_NEQ = "neq";
    const FILTER_TYPE_LIKE = 'like';

    const FILTER_LOGIC_AND = "and";
    const FILTER_LOGIC_OR = "or";

    /**
     * @param array  $data    Data to filter.
     * @param array  $filters Filters to apply to make the filtering.
     * @param string $logic   Logic to use for the filtering, AND, OR.
     *
     * @return mixed
     */
    public function filterData($data, $filters, $logic);

    /**
     * Get value from an associative array.
     *
     * @param $line
     * @param $field
     *
     * @return string
     */
    public function getFieldValue($line, $field);
}