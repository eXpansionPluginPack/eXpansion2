<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid\Column;


/**
 * Class TextColumn
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class TextColumn extends AbstractColumn
{
    /** @var bool  */
    protected $sortable;

    /** @var bool  */
    protected $translatable;

    /**
     * TextColumn constructor.
     *
     * @param string  $key
     * @param string  $name
     * @param float   $widthCoeficiency
     * @param boolean $sortable
     * @param boolean $translatable
     */
    public function __construct($key, $name, $widthCoeficiency, $sortable, $translatable)
    {
        parent::__construct($key, $name, $widthCoeficiency);

        $this->sortable = $sortable;
        $this->translatable = $translatable;
    }

    /**
     * @return bool
     */
    public function isSortable()
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
     * @return bool
     */
    public function isTranslatable()
    {
        return $this->translatable;
    }

    /**
     * @param bool $translatable
     */
    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;
    }
}