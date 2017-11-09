<?php


namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use FML\Controls\Frame;

/**
 * Class RecordsWindowFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins\Gui;
 * @author  reaby
 */
abstract class GridWindowFactory extends WindowFactory
{
    /** @var array */
    protected $genericData = [];

    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var Time */
    protected $timeFormatter;

    /**
     * @var GridBuilder
     */
    protected $gridBuilder;

    /** @var  Frame */
    protected $gridFrame;

    /** @var  float|null */
    protected $gridWidth;

    /** @var  float|null */
    protected $gridHeight;

    /** @var  float|null */
    protected $gridPosX = 0.;

    /** @var  float|null */
    protected $gridPosY = 0;

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $gridFrame = new Frame();
        $manialink->setData("gridFrame", $gridFrame);

        $manialink->getContentFrame()->addChild($manialink->getData('gridFrame'));

        $this->createGrid($manialink);

        $collection = $this->dataCollectionFactory->create($this->getData());
        $collection->setPageSize(20);
        $manialink->setData("dataCollection", $collection);

    }


    /**
     * @param ManialinkInterface $manialink
     */
    abstract protected function createGrid(ManialinkInterface $manialink);


    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getContentFrame();

        $manialink->getData('gridFrame')->removeAllChildren();

        /** @var GridBuilder $gridBuilder */
        $gridBuilder = $manialink->getData('grid');
        $gridBuilder->setDataCollection($manialink->getData('dataCollection'));

        if (!$this->gridHeight) {
            $this->gridHeight = $contentFrame->getHeight();
        }

        if (!$this->gridWidth) {
            $this->gridWidth = $contentFrame->getWidth();
        }

        $manialink->getData('gridFrame')->setPosition($this->gridPosX, $this->gridPosY);

        $manialink->getData("gridFrame")->addChild($gridBuilder->build($this->gridWidth,
            $this->gridHeight));

        parent::updateContent($manialink);
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->genericData = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->genericData;
    }

    /**
     * @param GridBuilderFactory $gridBuilderFactory
     */
    public function setGridBuilderFactory($gridBuilderFactory)
    {
        $this->gridBuilderFactory = $gridBuilderFactory;
    }

    /**
     * @param DataCollectionFactory $dataCollectionFactory
     */
    public function setDataCollectionFactory($dataCollectionFactory)
    {
        $this->dataCollectionFactory = $dataCollectionFactory;
    }

    /**
     * @param Time $time
     */
    public function setTimerFormatter(Time $time)
    {
        $this->timeFormatter = $time;
    }


    public function setGridSize($x, $y)
    {
        $this->gridWidth = $x;
        $this->gridHeight = $y;
    }

    public function setGridPosition($x, $y)
    {
        $this->gridPosX = $x;
        $this->gridPosY = $y;
    }

}
