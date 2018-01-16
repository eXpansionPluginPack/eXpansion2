<?php


namespace eXpansion\Bundle\Maps\Plugins\Gui;

use eXpansion\Bundle\Maps\Plugins\Jukebox;
use eXpansion\Bundle\Maps\Services\JukeboxService;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\Time;
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
     * @var AdminGroups
     */
    private $adminGroups;

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
     * @param AdminGroups $adminGroups
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
        JukeboxService $jukeboxService,
        AdminGroups $adminGroups
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);

        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->timeFormatter = $time;
        $this->sizeX = $sizeX;
        $this->sizeY = $sizeY;
        $this->jukeboxService = $jukeboxService;
        $this->adminGroups = $adminGroups;
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
        $this->setData($manialink, $this->updateMaps());

        $queueButton = $this->uiFactory->createButton('drop', uiButton::TYPE_DEFAULT);
        $queueButton->setTextColor("000")->setSize(12, 5)->setTranslate(true);

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
                3,
                true,
                false
            )->addTextColumn(
                'time',
                'expansion_maps.gui.window.column.goldtime',
                2,
                true,
                false
            )->addTextColumn(
                'nickname',
                'expansion_maps.gui.window.column.nickname',
                3,
                true,
                false
            );


        if ($this->adminGroups->hasPermission($manialink->getUserGroup(), "admin")) {
            $gridBuilder->addActionColumn('map', 'expansion_maps.gui.window.column.drop', 2,
                array($this, 'callbackDropMap'),
                $queueButton);
        }
        $contentFrame = $manialink->getContentFrame();
        $this->setGridSize($contentFrame->getWidth(), $contentFrame->getHeight() - 12);
        $this->setGridPosition(0, -10);
        $manialink->setData('grid', $gridBuilder);

    }

    public function callbackClear(ManialinkInterface $manialink, $login, $entries, $args)
    {
        $this->jukeboxPlugin->clear($login);
        $group = $this->groupFactory->createForPlayer($login);
        $this->setData($manialink, $this->updateMaps());
        $this->update($group);
    }

    public function callbackDrop(ManialinkInterface $manialink, $login, $entries, $args)
    {

        $this->jukeboxPlugin->drop($login, null);
        $group = $this->groupFactory->createForPlayer($login);
        $this->setData($manialink, $this->updateMaps());
        $this->update($group);

    }

    public function callbackDropMap(ManialinkInterface $manialink, $login, $params, $args)
    {
        $this->jukeboxPlugin->drop($login, $args['map']);
        $group = $this->groupFactory->createForPlayer($login);
        $this->setData($manialink, $this->updateMaps());
        $this->update($group);
    }


    public function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);
        $line = $this->uiFactory->createLayoutLine(0, 0, [], 2);

        $dropButton = $this->uiFactory->createButton("expansion_maps.gui.button.drop", uiButton::TYPE_DECORATED);
        $dropButton->setTranslate(true);
        $dropButton->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackDrop'], null));
        $line->addChild($dropButton);

        $clearButton = $this->uiFactory->createButton("expansion_maps.gui.button.clear");
        $clearButton->setAction($this->actionFactory->createManialinkAction($manialink, [$this, 'callbackClear'],
            null))
            ->setFocusColor('f00')
            ->setBorderColor('d00')
            ->setTextColor('fff')
            ->setTranslate(true);

        if ($this->adminGroups->hasPermission($manialink->getUserGroup(), "jukebox")) {
            $line->addChild($clearButton);
        }
        $manialink->addChild($line);
    }

    /**
     * @return array
     */
    public function updateMaps()
    {
        /**
         * @var string $i
         * @var Map $map
         */
        $i = 1;
        $data = [];
        foreach ($this->jukeboxService->getMapQueue() as $idx => $jbMap) {
            $map = $jbMap->getMap();
            $data[] = [
                'index' => $i++,
                'name' => $map->name,
                'nickname' => $jbMap->getPlayer()->getNickName(),
                'time' => $this->timeFormatter->timeToText($map->goldTime, true),
                'map' => $jbMap,
            ];
        }

        return $data;
    }


}
