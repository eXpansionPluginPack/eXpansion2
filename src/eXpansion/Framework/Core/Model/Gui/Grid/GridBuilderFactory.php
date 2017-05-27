<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 27/05/2017
 * Time: 11:14
 */

namespace eXpansion\Framework\Core\Model\Gui\Grid;

use eXpansion\Framework\Core\Model\Gui\Factory\LabelFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\LineFactory;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;


/**
 * Class GridBuilderFactory
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class GridBuilderFactory
{
    /** @var string */
    protected $class;

    /** @var ActionFactory */
    protected $actionFactory;

    /** @var LabelFactory */
    protected $labelFactory;

    /** @var LineFactory */
    protected $lineFactory;

    /** @var LineFactory */
    protected $titleLineFactory;

    /**
     * GridBuilderFactory constructor.
     *
     * @param string        $class
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        $class,
        ActionFactory $actionFactory,
        LabelFactory $labelFactory,
        LineFactory $lineFactory,
        LineFactory $titleLineFactory
    )
    {
        $this->class = $class;
        $this->actionFactory = $actionFactory;
        $this->labelFactory = $labelFactory;
        $this->lineFactory = $lineFactory;
        $this->titleLineFactory = $titleLineFactory;
    }

    /**
     * @return GridBuilder
     */
    public function create()
    {
        $class = $this->class;

        return new $class($this->actionFactory, $this->labelFactory, $this->lineFactory, $this->titleLineFactory);
    }
}
