<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 27/05/2017
 * Time: 19:48
 */

namespace eXpansion\Framework\Core\Model\Gui\Grid\Column;


/**
 * Class AbstractColumn
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid\Column;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
abstract class AbstractColumn
{
    protected $key;

    protected $name;

    protected $widthCoeficiency;

    /**
     * AbstractColumn constructor.
     *
     * @param $key
     * @param $name
     * @param $widthCoeficiency
     */
    public function __construct($key, $name, $widthCoeficiency)
    {
        $this->key = $key;
        $this->name = $name;
        $this->widthCoeficiency = $widthCoeficiency;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getWidthCoeficiency()
    {
        return $this->widthCoeficiency;
    }

    /**
     * @param mixed $widthCoeficiency
     */
    public function setWidthCoeficiency($widthCoeficiency)
    {
        $this->widthCoeficiency = $widthCoeficiency;
    }
}