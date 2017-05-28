<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid\Column;

use FML\Types\Renderable;


/**
 * Class ActionColumn
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ActionColumn extends AbstractColumn
{
    /** @var array */
    protected $callable;

    /** @var Renderable  */
    protected $renderer;

    /**
     * ActionColumn constructor.
     *
     * @param string     $key
     * @param string     $name
     * @param float      $widthCoeficiency
     * @param array      $callable
     * @param Renderable $renderer
     */
    public function __construct($key, $name, $widthCoeficiency, $callable, Renderable $renderer)
    {
        parent::__construct($key, $name, $widthCoeficiency);

        $this->callable = $callable;
        $this->renderer = $renderer;
    }

    /**
     * @return array
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * @param array $callable
     */
    public function setCallable($callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return Renderable
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param Renderable $renderer
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }
}