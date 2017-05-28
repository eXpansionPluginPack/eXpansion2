<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid\Column;


/**
 * Class AbstractColumn
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid\Column;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
abstract class AbstractColumn
{
    /** @var string  */
    protected $key;

    /** @var string  */
    protected $name;

    /** @var float  */
    protected $widthCoeficiency;

    /**
     * AbstractColumn constructor.
     *
     * @param string $key
     * @param string $name
     * @param float $widthCoeficiency
     */
    public function __construct($key, $name, $widthCoeficiency)
    {
        $this->key = $key;
        $this->name = $name;
        $this->widthCoeficiency = $widthCoeficiency;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getWidthCoeficiency()
    {
        return $this->widthCoeficiency;
    }

    /**
     * @param float $widthCoeficiency
     */
    public function setWidthCoeficiency($widthCoeficiency)
    {
        $this->widthCoeficiency = $widthCoeficiency;
    }
}