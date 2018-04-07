<?php


namespace eXpansion\Bundle\LocalRecords\Plugins\Gui;

use eXpansion\Bundle\LocalRecords\Model\Record;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\WindowFactory;
use FML\Controls\Frame;


/**
 * Class RecordsWindowFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordsWindowFactory extends WindowFactory
{
    /** @var Record[] */
    protected $recordsData = [];

    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var Time */
    protected $timeFormatter;

    /**
     * RecordsWindowFactory constructor.
     *
     * @param                       $name
     * @param                       $sizeX
     * @param                       $sizeY
     * @param null                  $posX
     * @param null                  $posY
     * @param DataCollectionFactory $dataCollectionFactory
     * @param WindowFactoryContext  $context
     * @param GridBuilderFactory    $gridBuilderFactory
     * @param Time                  $time
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        DataCollectionFactory $dataCollectionFactory,
        WindowFactoryContext $context,
        GridBuilderFactory $gridBuilderFactory,
        Time $time
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->timeFormatter = $time;
    }

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $collection = $this->dataCollectionFactory->create($this->getRecordsData());
        $collection->setPageSize(20);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'position',
                'expansion_local_records.gui.race.window.column.position',
                '1',
                true,
                true
            )->addTextColumn(
                'score',
                'expansion_local_records.gui.race.window.column.score',
                '2',
                true,
                true
            )->addTextColumn(
                'score_race',
                'expansion_local_records.gui.race.window.column.score_race',
                '2',
                true,
                true
            )->addTextColumn(
                'nickname',
                'expansion_local_records.gui.race.window.column.nickname',
                '4'
            )->addTextColumn(
                'login',
                'expansion_local_records.gui.race.window.column.login',
                '4',
                true,
                true
            );

        $manialink->setData('grid', $gridBuilder);

    }

    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        /** @var Frame $contentFrame */
        $contentFrame = $manialink->getContentFrame();
        $contentFrame->removeAllChildren();

        $collection = $this->dataCollectionFactory->create($this->getRecordsData());
        $collection->setPageSize(20);

        /** @var GridBuilder $gridBuilder */
        $gridBuilder = $manialink->getData('grid');
        $contentFrame->addChild($gridBuilder->build($contentFrame->getWidth(), $contentFrame->getHeight()));
    }

    /**
     * @return array
     */
    protected function getRecordsData()
    {
        $recordsData = [];
        foreach ($this->recordsData as $i => $record) {
            $recordsData[] = [
                'position' => $record['position'],
                'nickname' => $record['player']->getNickname(),
                'login' => $record['player']->getLogin(),
                'score' => $this->timeFormatter->timeToText($record['record']["1"]->getScore(), true),
                'score_race' => "<Not compatible>",
            ];

            if (isset($record['record']["other"])) {
                $record["score_race"] = $this->timeFormatter->timeToText($record['record']["other"]->getScore(), true);
            }
        }

        return $recordsData;
    }

    /**
     * @param Record[] $records
     */
    public function setRecordsData($records)
    {
        $this->recordsData = $records;
    }
}
