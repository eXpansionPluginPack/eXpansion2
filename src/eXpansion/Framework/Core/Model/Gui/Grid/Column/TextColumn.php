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
    protected $sortable;

    protected $translatable;

    public function __construct($key, $name, $widthCoeficiency, $sortable, $translatable)
    {
        parent::__construct($key, $name, $widthCoeficiency);

        $this->sortable = $sortable;
        $this->translatable = $translatable;
    }

    /**
     * @return mixed
     */
    public function getSortable()
    {
        return $this->sortable;
    }

    /**
     * @param mixed $sortable
     */
    public function setSortable($sortable)
    {
        $this->sortable = $sortable;
    }

    /**
     * @return mixed
     */
    public function getTranslatable()
    {
        return $this->translatable;
    }

    /**
     * @param mixed $translatable
     */
    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;
    }
}