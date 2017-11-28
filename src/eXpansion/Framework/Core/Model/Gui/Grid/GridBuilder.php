<?php

namespace eXpansion\Framework\Core\Model\Gui\Grid;

use eXpansion\Framework\Core\Model\Gui\Factory\LineFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\PagerFactory;
use eXpansion\Framework\Core\Model\Gui\Factory\TitleLineFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\Column\AbstractColumn;
use eXpansion\Framework\Core\Model\Gui\Grid\Column\ActionColumn;
use eXpansion\Framework\Core\Model\Gui\Grid\Column\InputColumn;
use eXpansion\Framework\Core\Model\Gui\Grid\Column\TextColumn;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;
use FML\Types\Renderable;


/**
 * Class GridBuilder
 *
 * @package eXpansion\Framework\Core\Model\Gui\Grid;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class GridBuilder
{

    /** @var  ActionFactory */
    protected $actionFactory;

    /** @var TitleLineFactory */
    protected $titleLineFactory;

    /** @var LineFactory */
    protected $lineFactory;

    /** @var PagerFactory */
    protected $pagerFactory;

    /** @var Factory */
    protected $uiFactory;

    /** @var DataCollectionInterface */
    protected $dataCollection;

    /** @var ManialinkInterface */
    protected $manialink;

    /** @var ManialinkFactoryInterface */
    protected $manialinkFactory;

    /** @var AbstractColumn[] */
    protected $columns;

    /** @var  float */
    protected $totalWidthCoefficency = 0.;

    /** @var int */
    protected $currentPage = 1;

    /** @var string */
    protected $pageKey;

    /** @var string */
    protected $actionPreviousPage;
    /** @var string */
    protected $actionNextPage;
    /** @var string */
    protected $actionLastPage;
    /** @var string */
    protected $actionFirstPage;
    /** @var  string */
    protected $actionGotoPage;
    /** @var string[] */
    protected $temporaryActions = [];

    /** @var array */
    protected $temporaryEntries = [];


    /**
     * GridBuilder constructor.
     *
     * @param ActionFactory $actionFactory
     * @param LineFactory $lineFactory
     * @param TitleLineFactory $titleLineFactory
     * @param PagerFactory $pagerFactory
     */
    public function __construct(
        ActionFactory $actionFactory,
        LineFactory $lineFactory,
        TitleLineFactory $titleLineFactory,
        PagerFactory $pagerFactory,
        Factory $uiFactory
    ) {
        $this->actionFactory = $actionFactory;
        $this->titleLineFactory = $titleLineFactory;
        $this->lineFactory = $lineFactory;
        $this->pagerFactory = $pagerFactory;
        $this->uiFactory = $uiFactory;

        $this->pageKey = "key_".spl_object_hash($this);
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
     * @param ManialinkInterface $manialink
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
        $this->actionGotoPage = $this->actionFactory
            ->createManialinkAction($manialink, array($this, 'goToPage'), []);

        return $this;
    }

    /**
     * Set the manialink factory responsible with the manialink.
     *
     * @param ManialinkFactoryInterface $manialinkFactory
     *
     * @return $this
     */
    public function setManialinkFactory(ManialinkFactoryInterface $manialinkFactory)
    {
        $this->manialinkFactory = $manialinkFactory;

        return $this;
    }

    /**
     * @param      string $key
     * @param      string $name
     * @param      integer $widthCoefficiency
     * @param bool $sortable
     * @param bool $translatable
     *
     * @return $this
     */
    public function addTextColumn($key, $name, $widthCoefficiency, $sortable = false, $translatable = false)
    {
        $this->columns[] = new TextColumn($key, $name, $widthCoefficiency, $sortable, $translatable);

        return $this;
    }

    /**
     * @param      string $key
     * @param      string $name
     * @param      integer $widthCoefficiency
     * @param bool $sortable
     * @param bool $translatable
     *
     * @return $this
     */
    public function addInputColumn($key, $name, $widthCoefficiency)
    {
        $this->columns[] = new InputColumn($key, $name, $widthCoefficiency);

        return $this;
    }


    /**
     * Add an action into a column.
     *
     * @param string $key
     * @param string $name
     * @param integer $widthCoefficiency
     * @param $action
     * @param Renderable $renderer
     *
     * @return $this
     */
    public function addActionColumn($key, $name, $widthCoefficiency, $action, $renderer)
    {
        $this->columns[] = new ActionColumn($key, $name, $widthCoefficiency, $action, $renderer);

        return $this;
    }

    /**
     * Remove all columns.
     */
    public function resetColumns()
    {
        $this->columns = [];
        $this->totalWidthCoefficency = 0.;
    }

    /**
     * Build a grid.
     *
     * @param double $width
     * @param double $height
     *
     * @return Frame
     */
    public function build($width, $height)
    {
        foreach ($this->temporaryActions as $action) {
            $this->actionFactory->destroyAction($action);
        }

        $lineHeight = 4.5;


        $frame = new Frame();
        $frame->setPosition(0, 0);
        $frame->setSize($width, $height);

        $posY = 0.;
        $tooltip = $this->uiFactory->createTooltip();
        $frame->addChild($tooltip);

        // Generating headers.
        $data = [];
        foreach ($this->columns as $columnData) {
            $action = null;
            $sort = "";
            if ($columnData->getSortable() && $columnData->getSortColumn()) {
                $sort = $columnData->getSortDirection();

            }
            if ($columnData->getSortable()) {
                $action = $this->actionFactory->createManialinkAction(
                    $this->manialink,
                    [$this, 'sortColumn'],
                    ["key" => $columnData->getKey()]);
            }

            $data[] = [
                'title' => $columnData->getName(),
                'width' => $columnData->getWidthCoeficiency(),
                'translatable' => true,
                'sort' => $sort,
                'action' => $action,
            ];
        }

        $frame->addChild($this->titleLineFactory->create($frame->getWidth(), $data));
        $posY -= $lineHeight + 1;

        /*
         * Display the main content.
         */
        $this->dataCollection->setPageSize(floor(($frame->getHeight() + $posY - $lineHeight - 2) / $lineHeight));

        $lines = $this->dataCollection->getData($this->currentPage);
        $idx = 0;
        foreach ($lines as $i => $lineData) {
            $data = [];
            foreach ($this->columns as $columnData) {
                if ($columnData instanceof TextColumn) {
                    $data[] = [
                        'text' => $this->dataCollection->getLineData($lineData, $columnData->getKey()),
                        'width' => $columnData->getWidthCoeficiency(),
                        'translatable' => $columnData->getTranslatable(),
                    ];
                } elseif ($columnData instanceof ActionColumn) {
                    $action = $this->actionFactory
                        ->createManialinkAction($this->manialink, $columnData->getCallable(), $lineData);
                    $this->temporaryActions[] = $action;
                    $data[] = [
                        'renderer' => clone $columnData->getRenderer(),
                        'width' => $columnData->getWidthCoeficiency(),
                        'action' => $action,
                    ];
                } elseif ($columnData instanceof InputColumn) {
                    $value = $this->dataCollection->getLineData($lineData, $columnData->getKey());

                    $data[] = [
                        'input' => $value,
                        'index' => $i,
                        'tooltip' => $tooltip,
                        'width' => $columnData->getWidthCoeficiency(),
                    ];
                }
            }
            $line = $this->lineFactory->create($frame->getWidth(), $data, $idx++);
            $line->setPosition(0, $posY);
            $frame->addChild($line);
            $posY -= $lineHeight;
        }

        /*
         * Handle the pager.
         */
        $posY = ($frame->getHeight() - 9) * -1;
        $pager = $this->pagerFactory->create(
            $frame->getWidth(),
            $this->currentPage,
            $this->dataCollection->getLastPageNumber(),
            $this->actionFirstPage,
            $this->actionPreviousPage,
            $this->actionNextPage,
            $this->actionLastPage,
            $this->actionGotoPage
        );
        $pager->setPosition(($frame->getWidth() - $pager->getWidth()) / 2, $posY);
        $frame->addChild($pager);

        return $frame;
    }

    /**
     * Action callback to go to the first page.
     */
    public function goToFirstPage(ManialinkInterface $manialink, $login = null, $entries = [])
    {
        $this->updateDataCollection($entries);
        $this->changePage(1);
    }

    /**
     * Action callback to go to the previous page.
     */
    public function goToPreviousPage(ManialinkInterface $manialink, $login = null, $entries = [])
    {
        $this->updateDataCollection($entries);
        if ($this->currentPage - 1 >= 1) {
            $this->changePage($this->currentPage - 1);
        }
    }

    /**
     * Action callback to go to the next page.
     */
    public function goToNextPage(ManialinkInterface $manialink, $login = null, $entries = [])
    {
        $this->updateDataCollection($entries);
        if ($this->currentPage + 1 <= $this->dataCollection->getLastPageNumber()) {
            $this->changePage($this->currentPage + 1);
        }
    }

    public function goToPage(ManialinkInterface $manialink, $login = null, $entries = [])
    {
        if (array_key_exists("pager_gotopage", $entries)) {
            if (is_numeric($entries['pager_gotopage'])) {
                $page = (int)$entries['pager_gotopage'];

                $this->updateDataCollection($entries);
                if (($page >= 1) && ($page <= $this->dataCollection->getLastPageNumber())) {
                    $this->changePage($page);
                }
            }
        }
    }


    /**
     * Action callback to go to the last page.
     */
    public function goToLastPage(ManialinkInterface $manialink, $login = null, $entries = [])
    {
        $this->updateDataCollection($entries);
        $this->changePage($this->dataCollection->getLastPageNumber());
    }

    /**
     * Updates dataCollection from entries.
     */
    public function updateDataCollection($entries)
    {
        $process = false;
        $data = [];
        $start = ($this->currentPage - 1) * $this->dataCollection->getPageSize();
        foreach ($entries as $key => $value) {
            if (substr($key, 0, 6) == "entry_") {
                $array = explode("_", str_replace("entry_", "", $key));
                setType($value, $array[1]);
                $data[$array[0]] = $value;
                $process = true;
            }
        }
        if ($process) {
            $lines = $this->dataCollection->getData($this->currentPage);
            $counter = 0;
            foreach ($lines as $i => $lineData) {
                $newData = $lineData;
                foreach ($this->columns as $columnData) {
                    if ($columnData instanceof InputColumn) {
                        $newData[$columnData->getKey()] = $data[$counter];
                    }
                }
                $this->dataCollection->setDataByIndex($start + $counter, $newData);
                $counter++;
            }
        }
    }

    /** get dataCollection
     *
     * @return DataCollectionInterface
     */
    public function getDataCollection()
    {
        return $this->dataCollection;
    }

    /**
     * Handle page change & refresh user window.
     *
     * @param integer $page
     */
    protected function changePage($page)
    {
        $this->currentPage = $page;
        $this->manialinkFactory->update($this->manialink->getUserGroup());
    }

    public function sortColumn(ManialinkInterface $manialink, $login, $entries, $args)
    {
        foreach ($this->columns as $columnData) {
            if ($columnData->getKey() == $args['key']) {
                if ($columnData->getSortColumn()) {
                    $columnData->toggleSortDirection();
                } else {
                    $columnData->setSortColumn(true);
                }
                $this->dataCollection->setFiltersAndSort([], $columnData->getKey(), $columnData->getSortDirection());
            } else {
                $columnData->setSortColumn(false);
            }
        }

        $this->manialinkFactory->update($manialink->getUserGroup());
    }
}
