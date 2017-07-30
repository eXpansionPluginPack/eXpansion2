<?php


namespace eXpansion\Bundle\Maps\Plugins\Gui;

use eXpansion\Bundle\Acme\Plugins\Gui\WindowFactory;
use eXpansion\Bundle\LocalRecords\Entity\Record;
use eXpansion\Bundle\Maps\Plugins\Jukebox;
use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Gui\Components\uiButton;
use FML\Controls\Frame;
use Maniaplanet\DedicatedServer\Structures\Map;


/**
 * Class RecordsWindowFactory
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins\Gui;
 * @author  reaby
 */
class JukeboxWindowFactory extends GridWindowFactory
{
    public $sizeX;
    public $sizeY;

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
     * @var JukeboxService
     */
    private $jukeboxService;
    /**
     * @var WindowFactoryContext
     */
    private $context;

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
     * @param JukeboxService $jukeboxService
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
        JukeboxService $jukeboxService
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->timeFormatter = $time;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->jukeboxService = $jukeboxService;
    }

    public function setJukeboxPlugin(Jukebox $plugin)
    {
        $this->jukeboxPlugin = $plugin;
    }

    /**
     * @param ManialinkInterface $manialink
     * @return void
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $collection = $this->dataCollectionFactory->create($this->getData());
        $collection->setPageSize(20);

        $queueButton = $this->uiFactory->createButton('expansion_maps.gui.window.button.drop', uiButton::TYPE_DEFAULT);
        $queueButton->setTextColor("000")->setSize(12, 5);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'index',
                'expansion_maps.gui.window.column.index',
                1,
                true,
                true
            )->addTextColumn(
                'name',
                'expansion_maps.gui.window.column.name',
                3,
                true,
                true
            )->addTextColumn(
                'time',
                'expansion_maps.gui.window.column.goldtime',
                2,
                true,
                true
            )->addTextColumn(
                'nickname',
                'expansion_maps.gui.window.column.nickname',
                3
            )
            ->addActionColumn('map', 'expansion_maps.gui.window.column.drop', 2, array($this, 'callbackDropMap'),
                $queueButton);


        $manialink->setData('grid', $gridBuilder);

    }

    public function callbackClear($login)
    {
        $this->jukeboxPlugin->clear($login);
        $this->jukeboxPlugin->view($login);
    }

    public function callbackDrop($login)
    {
        $this->jukeboxPlugin->drop($login, null);
        $this->jukeboxPlugin->view($login);

    }

    public function callbackDropMap($login, $params, $args)
    {
        $this->jukeboxPlugin->drop($login, $args['map']);
        $this->jukeboxPlugin->view($login);
    }

    public function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $line = $this->uiFactory->createLayoutLine(0, -10, [], 2);

        $dropButton = $this->uiFactory->createButton("expansion_maps.gui.window.button.drop", uiButton::TYPE_DECORATED);
        $dropButton->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackDrop'], null));
        $line->addChild($dropButton);

        $clearButton = $this->uiFactory->createButton("expansion_maps.gui.window.button.clear");
        $clearButton->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackClear'], null))
            ->setFocusColor('f00')
            ->setBorderColor('d00')
            ->setTextColor('fff');
        $line->addChild($clearButton);

        $manialink->addChild($dropButton);
        $manialink->addChild($line);
    }

    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
        $this->updateMaps();
    }

    public function updateMaps()
    {
        /**
         * @var string $i
         * @var Map $map
         */
        $this->genericData = [];

        $i = 1;
        foreach ($this->jukeboxService->getMapQueue() as $idx => $jbMap) {
            $map = $jbMap->getMap();
            $this->genericData[] = [
                'index' => $i++,
                'name' => $map->name,
                'nickname' => $jbMap->getPlayer()->getNickName(),
                'time' => $this->timeFormatter->timeToText($map->goldTime, true),
                'map' => $jbMap,
            ];
        }

        echo "updated!".count($this->genericData);

    }


}
