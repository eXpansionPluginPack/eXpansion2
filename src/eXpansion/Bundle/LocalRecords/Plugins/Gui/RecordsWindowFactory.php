<?php


namespace eXpansion\Bundle\LocalRecords\Plugins\Gui;
use eXpansion\Bundle\Acme\Plugins\Gui\WindowFactory;
use eXpansion\Bundle\LocalRecords\DataProviders\Listener\RecordsDataListener;
use eXpansion\Bundle\LocalRecords\Entity\Record;
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
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RecordsWindowFactory extends WindowFactory implements RecordsDataListener
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
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $collection = $this->dataCollectionFactory->create($this->getRecordsData());
        $collection->setPageSize(20);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'position',
                'expansion_local_records.gui.race.window.column.position',
                '1'
            )->addTextColumn(
                'nickname',
                'expansion_local_records.gui.race.window.column.nickname',
                '4'
            )->addTextColumn(
                'login',
                'expansion_local_records.gui.race.window.column.login',
                '4'
            )->addTextColumn(
                'score',
                'expansion_local_records.gui.race.window.column.score',
                '3'
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
                'position' => $i + 1,
                'nickname' => $record->getPlayerLogin(),
                'login' => $record->getPlayerLogin(),
                'score' => $this->timeFormatter->milisecondsToTrackmania($record->getScore(), true),
            ];
        }

        return $recordsData;
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


    /**
     * Called when local records are loaded.
     *
     * @param Record[] $records
     */
    public function onLocalRecordsLoaded($records)
    {
        $this->recordsData = $records;
    }

    /**
     * Called when a player finishes map for the very first time (basically first record).
     *
     * @param Record   $record
     * @param Record[] $records
     * @param          $position
     */
    public function onLocalRecordsFirstRecord(Record $record, $records, $position)
    {
        $this->recordsData = $records;
    }

    /**
     * Called when a player finishes map and does same time as before.
     *
     * @param Record   $record
     * @param Record   $oldRecord
     * @param Record[] $records
     */
    public function onLocalRecordsSameScore(Record $record, Record $oldRecord, $records)
    {
        $this->recordsData = $records;
    }

    /**
     * Called when a player finishes map with better time and has better position.
     *
     * @param Record   $record
     * @param Record   $oldRecord
     * @param Record[] $records
     * @param int      $position
     * @param int      $oldPosition
     */
    public function onLocalRecordsBetterPosition(Record $record, Record $oldRecord, $records, $position, $oldPosition)
    {
        $this->recordsData = $records;
    }

    /**
     * Called when a player finishes map with better time but keeps same position.
     *
     * @param Record   $record
     * @param Record   $oldRecord
     * @param Record[] $records
     * @param          $position
     */
    public function onLocalRecordsSamePosition(Record $record, Record $oldRecord, $records, $position)
    {
        $this->recordsData = $records;
    }
}