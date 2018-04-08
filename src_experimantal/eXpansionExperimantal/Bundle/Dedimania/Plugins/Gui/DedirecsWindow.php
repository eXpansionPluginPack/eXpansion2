<?php


namespace eXpansionExperimantal\Bundle\Dedimania\Plugins\Gui;


use eXpansionExperimantal\Bundle\Dedimania\Structures\DedimaniaRecord;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;


/**
 * Class RecordsWindowFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins\Gui;
 * @author  reaby
 */
class DedirecsWindow extends GridWindowFactory
{
    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var Time */
    protected $timeFormatter;


    /**
     * MapsWindowFactory constructor.
     * @param                       $name
     * @param                       $sizeX
     * @param                       $sizeY
     * @param null                  $posX
     * @param null                  $posY
     * @param WindowFactoryContext  $context
     * @param GridBuilderFactory    $gridBuilderFactory
     * @param DataCollectionFactory $dataCollectionFactory
     * @param Time                  $time
     */
    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WindowFactoryContext $context,
        GridBuilderFactory $gridBuilderFactory,
        DataCollectionFactory $dataCollectionFactory,
        Time $time
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->timeFormatter = $time;
    }

    /**
     * @param DedimaniaRecord[] $records
     */
    public function setRecords($records)
    {
        $this->genericData = [];

        /**
         * @var string          $i
         * @var DedimaniaRecord $record
         */
        $i = 1;
        foreach ($records as $uid => $record) {
            $this->genericData[] = [
                'index' => $i++,
                'time' => $this->timeFormatter->timeToText($record->best, true),
                'name' => TMString::trimControls($record->nickName),
                'login' => $record->login,
            ];
        }

    }

    /**
     * @param ManialinkInterface $manialink
     * @return void
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $this->setData($manialink, $this->genericData);

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($manialink->getData('dataCollection'))
            ->setManialinkFactory($this)
            ->addTextColumn(
                'index',
                'expansion_dedimania.gui.window.column.index',
                1,
                true,
                false
            )->addTextColumn(
                'time',
                'expansion_dedimania.gui.window.column.time',
                2,
                true,
                false

            )->addTextColumn(
                'name',
                'expansion_dedimania.gui.window.column.name',
                5,
                true,
                false
            )->addTextColumn(
                'login',
                'expansion_dedimania.gui.window.column.login',
                3,
                false
            );

        $manialink->setData('grid', $gridBuilder);

    }

}
