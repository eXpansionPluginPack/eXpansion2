<?php

namespace eXpansion\Framework\Core\Model\Data;


/**
 * trait DataStorageTrait
 *
 * @package eXpansion\Framework\Core\Model\Data;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
trait DataStorageTrait
{
    /** @var  array */
    protected $data;

    /**
     * Set a certain data.
     *
     * @param $key
     * @param $value
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get a certain data.
     *
     * @param      $key
     * @param null $default
     *
     * @return null
     */
    public function getData($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
}