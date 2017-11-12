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
    /** @var bool */
    protected $sortable = false;

    /** @var string */
    protected $key;

    /** @var string */
    protected $name;

    /** @var float */
    protected $widthCoeficiency;

    /** @var string */
    protected $sortDirection = "ASC";
    /**
     * @var bool
     */
    protected $sortColumn = false;

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

    /**
     * @return bool
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
    }

    /**
     * @return string
     */
    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }

    /**
     * @param string $sortDirection
     */
    public function setSortDirection(string $sortDirection)
    {
        $this->sortDirection = $sortDirection;
    }

    /**
     * @return bool
     */
    public function getSortColumn(): bool
    {
        return $this->sortColumn;
    }

    /**
     * @param bool $sortColumn
     */
    public function setSortColumn(bool $sortColumn)
    {
        $this->sortColumn = $sortColumn;
    }

    public function toggleSortDirection()
    {
        if ($this->sortDirection == "ASC") {
            $this->sortDirection = "DESC";
        } else {
            $this->sortDirection = "ASC";
        }
    }
}
