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
     * @var
     */
    protected $gridBuilder;

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $this->createGrid($manialink);
    }


    /**
     * @param ManialinkInterface $manialink
     * @return mixed
     */
    abstract protected function createGrid(ManialinkInterface $manialink);


    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getContentFrame();
        $contentFrame->removeAllChildren();

        $collection = $this->dataCollectionFactory->create($this->getData());
        $collection->setPageSize(20);

        /** @var GridBuilder $gridBuilder */
        $gridBuilder = $manialink->getData('grid');
        $contentFrame->addChild($gridBuilder->build($contentFrame->getWidth(), $contentFrame->getHeight()));
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
    public function getData() {
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


}
