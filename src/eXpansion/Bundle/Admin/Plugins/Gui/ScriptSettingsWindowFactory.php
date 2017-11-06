<?php

namespace eXpansion\Bundle\Admin\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyManialink;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Gui\Components\uiAnimation;
use eXpansion\Framework\Gui\Components\uiButton;
use eXpansion\Framework\Gui\Components\uiLabel;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quad;
use Maniaplanet\DedicatedServer\Connection;


/**
 * Class MenuFactory
 *
 * @package eXpansion\Bundle\Menu\Plugins\Gui;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ScriptSettingsWindowFactory extends GridWindowFactory
{
    /** @var Console */
    protected $console;

    /** @var Connection */
    protected $connection;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var  AdminGroups */
    protected $adminGroupsHelper;

    public function __construct(
        $name,
        $sizeX,
        $sizeY,
        $posX,
        $posY,
        WindowFactoryContext $context,
        GridBuilderFactory $gridBuilderFactory,
        DataCollectionFactory $dataCollectionFactory,
        AdminGroups $adminGroupsHelper,
        Connection $connection,
        Console $console
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->currentMenuView = Frame::create();
        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->connection = $connection;
        $this->console = $console;
    }

    /**
     * @param ManialinkInterface $manialink
     * @return void
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $this->fetchScriptSettings();

        $collection = $this->dataCollectionFactory->create($this->getData());
        $collection->setPageSize(20);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'name',
                'expansion_admin.gui.window.scriptsettings.column.name',
                5,
                true,
                false
            )->addInputColumn(
                'value',
                'expansion_admin.gui.window.scriptsettings.column.value',
                3);

        $manialink->setData('grid', $gridBuilder);
        $frame = $manialink->getContentFrame();
        $this->setGridSize($frame->getWidth(), $frame->getHeight() - 10);

        $apply = $this->uiFactory->createButton("Apply");
        $apply->setPosition(($frame->getWidth() - $apply->getWidth()), -($frame->getHeight() - $apply->getHeight()));

        $apply->setAction($this->actionFactory->createManialinkAction(
            $manialink, [$this, "callbackApply"], ["grid" => $manialink->getData('grid')]));

        $manialink->addChild($apply);

    }

    public function callbackApply($login, $entries, $args)
    {
        /** @var GridBuilder $grid */
        $grid = $args['grid'];
        $grid->updateDataCollection($entries);

        $settings = [];
        foreach ($grid->getDataCollection()->getAll() as $key => $value) {
            $settings[$value['name']] = $value['value'];
        }

        try {
            $this->connection->setModeScriptSettings($settings);
        } catch (\Exception $ex) {
            $this->connection->chatSendServerMessage("error: ".$ex->getMessage());
            $this->console->writeln('$f00Error: $fff'.$ex->getMessage());
        }
    }


    public function fetchScriptSettings()
    {
        $this->genericData = [];

        $scriptSettings = $this->connection->getModeScriptSettings();

        /**
         * @var string $i
         */
        $i = 1;
        foreach ($scriptSettings as $name => $value) {
            $this->genericData[] = [
                'index' => $i++,
                'name' => $name,
                'value' => $value,
            ];
        }
    }
}
