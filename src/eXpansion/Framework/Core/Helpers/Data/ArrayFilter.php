<?php

namespace eXpansion\Framework\Core\Helpers\Data;

use eXpansion\Framework\Core\Exceptions\Data\Filter\InvalidFilterTypeException;
use eXpansion\Framework\Core\Model\Data\FilterInterface;


/**
 * Class ArrayFilter
 *
 * @package eXpansion\Framework\Core\Helpers\Data;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ArrayFilter implements FilterInterface
{
    /**
     * @inheritdoc
     */
    public function filterData($data, $filters, $logic = self::FILTER_LOGIC_AND)
    {
        if (empty($filters)) {
            return $data;
        }

        $filteredData = [];
        foreach ($data as $line) {
            $allTrue = $logic == self::FILTER_LOGIC_AND;

            foreach ($filters as $field => $condition) {
                $result = $this->checkValue($this->getFieldValue($line, $field), $condition);

                if ($result && $logic == self::FILTER_LOGIC_OR) {
                    $allTrue = true;
                    break;
                } else if ($logic == self::FILTER_LOGIC_AND) {
                    if (!$result) {
                        $allTrue = false;
                        break;
                    }
                }
            }

            if ($allTrue) {
                $filteredData[] = $line;
            }
        }

        return $filteredData;
    }

    /**
     * Check if value respects condition.
     *
     * @param string $value
     * @param $condition
     *
     * @return boolean|null
     * @throws InvalidFilterTypeException
     */
    protected function checkValue($value, $condition) {
        list($filterType, $valueToCheck) = $condition;

        switch ($filterType) {
            case self::FILTER_TYPE_EQ:
                return $valueToCheck == $value;
                break;

            case self::FILTER_TYPE_NEQ:
                return $valueToCheck != $value;
                break;

            case self::FILTER_TYPE_LIKE:
                return strpos($value, $valueToCheck) !== false;
                break;

            default :
                throw new InvalidFilterTypeException("Filter type '$filterType' is unknown'");
        }
    }

    /**
     * Get value from an associative array.
     *
     * @param $line
     * @param $field
     *
     * @return string
     */
    public function getFieldValue($line, $field)
    {
        return isset($line[$field]) ? $line[$field] : '';
    }
}
