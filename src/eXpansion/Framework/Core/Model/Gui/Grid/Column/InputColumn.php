<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid\Column;


/**
 * Class TextColumn
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class InputColumn extends AbstractColumn
{

    /** @var string */
    protected $entryName;

    /**
     * TextColumn constructor.
     *
     * @param string $key
     * @param string $name
     * @param float $widthCoeficiency
     * @param boolean $sortable
     * @param boolean $translatable
     */
    public function __construct($key, $name, $widthCoeficiency)
    {
        parent::__construct($key, $name, $widthCoeficiency);
    }

    /**
     * @return string
     */
    public function getEntryName(): string
    {
        return $this->entryName;
    }

    /**
     * @param string $entryName
     */
    public function setEntryName(string $entryName)
    {
        $this->entryName = $entryName;
    }

}
