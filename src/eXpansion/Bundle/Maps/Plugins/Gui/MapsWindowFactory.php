<?php


namespace eXpansion\Bundle\Maps\Plugins\Gui;

use eXpansion\Bundle\Acme\Plugins\Gui\WindowFactory;
use eXpansion\Bundle\LocalRecords\Entity\Record;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use FML\Controls\Frame;
use Maniaplanet\DedicatedServer\Structures\Map;


/**
 * Class RecordsWindowFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins\Gui;
 * @author  reaby
 */
class MapsWindowFactory extends GridWindowFactory
{

    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var Time */
    protected $timeFormatter;

    /**
     * @param ManialinkInterface $manialink
     * @return void
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $collection = $this->dataCollectionFactory->create($this->getData());
        $collection->setPageSize(20);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'index',
                'maps.gui.window.column.index',
                '1',
                true
            )->addTextColumn(
                'name',
                'maps.gui.window.column.name',
                '3',
                true
            )->addTextColumn(
                'author',
                'maps.gui.window.column.author',
                '4'
            )->addTextColumn(
                'time',
                'maps.gui.window.column.goldtime',
                '3',
                true
            );

        $manialink->setData('grid', $gridBuilder);
    }


    public function setMaps($maps)
    {
        /**
         * @var string $i
         * @var Map $map
         */
        $i = 0;
        foreach ($maps as $uid => $map) {
            $this->genericData[] = [
                'index' => $i + 1,
                'name' => $map->name,
                'author' => $map->author,
                'time' => $this->timeFormatter->timeToText($map->goldTime, true),
            ];
        }
    }

}
