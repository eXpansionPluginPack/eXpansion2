<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid;

use eXpansion\Framework\Core\Model\Gui\Factory\LineFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\PagerFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\TitleLineFactory;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Ui\Factory;


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

    /** @var LineFactory */
    protected $lineFactory;

    /** @var TitleLineFactory */
    protected $titleLineFactory;

    /** @var PagerFactory */
    protected $pagerFactory;

    /** @var PagerFactory */
    protected $uiFactory;

    /**
     * GridBuilderFactory constructor.
     *
     * @param string $class
     * @param ActionFactory $actionFactory
     * @param LineFactory $lineFactory
     * @param TitleLineFactory $titleLineFactory
     * @param PagerFactory $pagerFactory
     * @param Factory $uiFactory
     */
    public function __construct(
        $class,
        ActionFactory $actionFactory,
        LineFactory $lineFactory,
        TitleLineFactory $titleLineFactory,
        PagerFactory $pagerFactory,
        Factory $uiFactory
    )
    {
        $this->class = $class;
        $this->actionFactory = $actionFactory;
        $this->lineFactory = $lineFactory;
        $this->titleLineFactory = $titleLineFactory;
        $this->pagerFactory = $pagerFactory;
        $this->uiFactory = $uiFactory;
    }

    /**
     * @return GridBuilder
     */
    public function create()
    {
        $class = $this->class;
        return new $class($this->actionFactory, $this->lineFactory, $this->titleLineFactory, $this->pagerFactory, $this->uiFactory);
    }
}
