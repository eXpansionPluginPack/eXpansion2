<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 21:57
 */

namespace eXpansion\Framework\Core\Helpers\Data;

use eXpansion\Framework\Core\Exceptions\Data\Filter\InvalidFilterTypeException;
use eXpansion\Framework\Core\Model\Data\FilterInterface;


/**
 * Class ArrayFilter
 *
 * @package eXpansion\Framework\Core\Helpers\Data;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ObjectFilter extends ArrayFilter
{
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
        return $line->$field();
    }
}