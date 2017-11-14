<?php


namespace eXpansion\Bundle\Maps\Plugins\Gui;


use eXpansion\Bundle\Maps\Plugins\Jukebox;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Helpers\TMString;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
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
     * @var Jukebox
     */
    private $jukeboxPlugin;

    /**
     * MapsWindowFactory constructor.
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param null $posX
     * @param null $posY
     * @param WindowFactoryContext $context
     * @param GridBuilderFactory $gridBuilderFactory
     * @param DataCollectionFactory $dataCollectionFactory
     * @param Time $time
     * @param Jukebox $jukeboxPlugin
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
        Time $time,
        Jukebox $jukeboxPlugin
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->timeFormatter = $time;
        $this->jukeboxPlugin = $jukeboxPlugin;
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

        $queueButton = $this->uiFactory->createButton('+', uiButton::TYPE_DECORATED);
        $queueButton->setTextColor("fff")->setSize(5, 5);
        $tooltip->addTooltip($queueButton, 'Adds map to jukebox');

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($manialink->getData('dataCollection'))
            ->setManialinkFactory($this)
            ->addTextColumn(
                'index',
                'expansion_maps.gui.window.column.index',
                1,
                true,
                false
            )->addTextColumn(
                'name',
                'expansion_maps.gui.window.column.name',
                5,
                true,
                false
            )->addTextColumn(
                'author',
                'expansion_maps.gui.window.column.author',
                3,
                false
            )->addTextColumn(
                'time',
                'expansion_maps.gui.window.column.goldtime',
                2,
                true,
                false

            )->addActionColumn('wish', 'expansion_maps.gui.window.column.wish', 1, array($this, 'callbackWish'),
                $queueButton);

        $manialink->setData('grid', $gridBuilder);

    }


    public function callbackWish(ManialinkInterface $manialink, $login, $params, $args)
    {
        $this->jukeboxPlugin->add($login, $args['index']);
    }


    public function setMaps($maps)
    {
        $this->genericData = [];

        /**
         * @var string $i
         * @var Map $map
         */
        $i = 1;
        foreach ($maps as $uid => $map) {
            $this->genericData[] = [
                'index' => $i++,
                'name' => TMString::trimControls($map->name),
                'author' => $map->author,
                'time' => $this->timeFormatter->timeToText($map->goldTime, true),
                'wish' => $map,
            ];
        }

    }

}
