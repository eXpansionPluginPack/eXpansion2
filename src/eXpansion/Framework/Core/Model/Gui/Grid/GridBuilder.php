<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 27/05/2017
 * Time: 10:38
 */

namespace eXpansion\Framework\Core\Model\Gui\Grid;
use eXpansion\Framework\Core\Model\Gui\Factory\LabelFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\LineFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quads\Quad_Icons64x64_1;


/**
 * Class GridBuilder
 *
 * @TODO Add actions on elements.
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class GridBuilder
{
    /** @var  ActionFactory */
    protected $actionFactory;

    /** @var LabelFactory  */
    protected $labelFactory;

    /** @var LineFactory */
    protected $titleLineFactory;

    /** @var LineFactory */
    protected $lineFactory;

    /** @var DataCollectionInterface */
    protected $dataCollection;

    /** @var ManialinkInterface */
    protected $manialink;

    /** @var ManialinkFactory */
    protected $manialinkFactory;

    /** @var  array */
    protected $columns;

    /** @var  float */
    protected $totalWidthCoefficency = 0;

    /** @var int */
    protected $currentPage = 1;

    /** @var string */
    protected $pageKey;

    protected $actionFirstPage;
    protected $actionPreviousPage;
    protected $actionNextPage;
    protected $actionLastPage;

    /**
     * GridBuilder constructor.
     *
     * @param ActionFactory $actionFactory
     */
    public function __construct(
        ActionFactory $actionFactory,
        LabelFactory $labelFactory,
        LineFactory $lineFactory,
        LineFactory $titleLineFactory
    ) {
        $this->actionFactory = $actionFactory;
        $this->labelFactory = $labelFactory;
        $this->titleLineFactory = $titleLineFactory;
        $this->lineFactory = $lineFactory;

        $this->pageKey = spl_object_hash($this) . "_key";
    }

    /**
     * Set the data collection.
     *
     * @param DataCollectionInterface $dataCollection
     *
     * @return $this
     */
    public function setDataCollection(DataCollectionInterface $dataCollection)
    {
        $this->dataCollection = $dataCollection;

        return $this;
    }

    /**
     * Set the manialink the content is generated for.
     *
     * @param DataCollectionInterface $manialink
     *
     * @return $this
     */
    public function setManialink(ManialinkInterface $manialink)
    {
        $this->manialink = $manialink;

        $this->actionFirstPage = $this->actionFactory
            ->createManialinkAction($manialink, array($this, 'goToFirstPage'), []);
        $this->actionPreviousPage = $this->actionFactory
            ->createManialinkAction($manialink, array($this, 'goToPreviousPage'), []);
        $this->actionNextPage = $this->actionFactory
            ->createManialinkAction($manialink, array($this, 'goToNextPage'), []);
        $this->actionLastPage = $this->actionFactory
            ->createManialinkAction($manialink, array($this, 'goToLastPage'), []);

        return $this;
    }

    /**
     * Set the manialink factory responsible with the manialink.
     *
     * @param ManialinkFactory $manialinkFactory
     *
     * @return $this
     */
    public function setManialinkFactory($manialinkFactory)
    {
        $this->manialinkFactory = $manialinkFactory;

        return $this;
    }

    /**
     * Add a column to the display
     *
     * @param $key
     * @param $name
     * @param $widthCoefficiency
     *
     * @return $this
     */
    public function addColumn($key, $name, $widthCoefficiency, $sortable = false, $translatable = false)
    {
        $this->columns[] = [$key, $name, $widthCoefficiency, $sortable, $translatable];
        $this->totalWidthCoefficency += $widthCoefficiency;

        return $this;
    }

    /**
     * Remove all columns.
     */
    public function resetColumns()
    {
        $this->columns = [];
        $this->totalWidthCoefficency = 0;
    }

    public function build($width, $height)
    {
        $lineHeight = 5 + 0.5;

        $frame = new Frame();
        $frame->setPosition(0,0);
        $frame->setSize($width, $height);

        $posY = 0;
        // Generating headers.
        // TODO Add proper background if sortable create action...
        $data = [];
        foreach ($this->columns as $columnData) {
            list($key, $name, $widthCoefficiency, $sortable) = $columnData;
            $data[] = ['text' => $name, 'width' => $widthCoefficiency];
        }
        $frame->addChild($this->titleLineFactory->create($frame->getWidth(), $data));

        $posY -= $lineHeight + 1;

        /*
         * Display the main content.
         */
        $this->dataCollection->setPageSize(floor(($frame->getHeight() + $posY - $lineHeight - 2) / $lineHeight));

        $lines = $this->dataCollection->getData($this->currentPage);
        foreach ($lines as $i => $lineData) {
            $data = [];
            foreach ($this->columns as $columnData) {
                list($key, $name, $widthCoefficiency, $sortable, $translatable) = $columnData;

                $data[] = [
                    'text' => $this->dataCollection->getLineData($lineData, $key),
                    'width' => $widthCoefficiency,
                    'translatable' => $translatable
                ];
            }
            $line = $this->lineFactory->create($frame->getWidth(), $data, $i);
            $line->setPosition(0, $posY);
            $frame->addChild($line);
            $posY -= $lineHeight;
        }

        /*
         * Handle the pager.
         */
        $buttonSize = 7;
        $posY = ($frame->getHeight() -7) * -1;
        if ($this->currentPage > 2) {
            $button = Quad_Icons64x64_1::create();
            $button->setSubStyle(Quad_Icons64x64_1::SUBSTYLE_ArrowFirst)
                ->setSize($buttonSize,$buttonSize)
                ->setPosition(1, $posY)
                ->setAction($this->actionFirstPage);
            $frame->addChild($button);
        }
        if ($this->currentPage > 1) {
            $button = Quad_Icons64x64_1::create();
            $button->setSubStyle(Quad_Icons64x64_1::SUBSTYLE_ArrowPrev)
                ->setSize($buttonSize,$buttonSize)
                ->setPosition(2 + $buttonSize, $posY)
                ->setAction($this->actionPreviousPage);
            $frame->addChild($button);
        }
        if ($this->currentPage < $this->dataCollection->getLastPageNumber()) {
            $button = Quad_Icons64x64_1::create();
            $button->setSubStyle(Quad_Icons64x64_1::SUBSTYLE_ArrowNext)
                ->setSize($buttonSize,$buttonSize)
                ->setPosition($frame->getWidth() - 1 - (2*$buttonSize), $posY)
                ->setAction($this->actionNextPage);
            $frame->addChild($button);
        }
        if ($this->currentPage < $this->dataCollection->getLastPageNumber() - 1) {
            $button = Quad_Icons64x64_1::create();
            $button->setSubStyle(Quad_Icons64x64_1::SUBSTYLE_ArrowLast)
                ->setSize($buttonSize,$buttonSize)
                ->setPosition($frame->getWidth() - 1 - $buttonSize, $posY)
                ->setAction($this->actionLastPage);
            $frame->addChild($button);
        }

        return $frame;
    }

    public function goToFirstPage()
    {
        $this->changePage(1);
    }

    public function goToPreviousPage()
    {
        if ($this->currentPage - 1 >= 1) {
            $this->changePage($this->currentPage - 1);
        }
    }
    public function goToNextPage()
    {
        if ($this->currentPage + 1 <= $this->dataCollection->getLastPageNumber()) {
            $this->changePage($this->currentPage + 1);
        }
    }
    public function goToLastPage()
    {
        $this->changePage($this->dataCollection->getLastPageNumber());
    }

    protected function changePage($page)
    {
        $this->currentPage = $page;
        $this->manialinkFactory->update($this->manialink->getUserGroup());
    }

    /**
     * Get the columns with their final width
     *
     * @param float $width Width of each columns
     *
     * @return \Generator
     */
    protected function getColumns($width)
    {
        $coef = $width / $this->totalWidthCoefficency;

        foreach ($this->columns as $columnData) {
            list($key, $name, $widthCoefficiency, $sortable, $translatable) = $columnData;

            yield [$key, $name, $widthCoefficiency * $coef, $sortable, $translatable];
        }
    }
}