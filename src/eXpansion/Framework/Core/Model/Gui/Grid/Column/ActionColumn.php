<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid\Column;


/**
 * Class ActionColumn
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ActionColumn extends AbstractColumn
{
    protected $callable;

    protected $renderer;

    public function __construct($key, $name, $widthCoeficiency, $sortable, $translatable)
    {
        parent::__construct($key, $name, $widthCoeficiency);

        $this->callable = $sortable;
        $this->renderer = $translatable;
    }

    /**
     * @return mixed
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @param mixed $callable
     */
    public function setCallable($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return mixed
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param mixed $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }
}