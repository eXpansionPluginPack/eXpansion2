<?php

namespace eXpansion\Bundle\Admin\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Window;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use FML\Controls\Frame;
use FML\Script\Script;
use Maniaplanet\DedicatedServer\Connection;


/**
 * Class Script settings factory
 *
 * @package eXpansion\Bundle\Menu\Plugins\Gui;
 * @author reaby
 */
class ScriptSettingsWindowFactory extends GridWindowFactory
{
    /** @var Console */
    protected $console;

    /** @var Factory */
    protected $factory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var  AdminGroups */
    protected $adminGroupsHelper;

    /**
     * ScriptSettingsWindowFactory constructor.
     *
     * @param $name
     * @param $sizeX
     * @param $sizeY
     * @param $posX
     * @param $posY
     * @param WindowFactoryContext $context
     * @param GridBuilderFactory $gridBuilderFactory
     * @param DataCollectionFactory $dataCollectionFactory
     * @param AdminGroups $adminGroupsHelper
     * @param Factory $factory
     * @param Console $console
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
        AdminGroups $adminGroupsHelper,
        Factory $factory,
        Console $console
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->currentMenuView = Frame::create();
        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->factory = $factory;
        $this->console = $console;
    }

    /**
     * @param ManialinkInterface|Window $manialink
     * @return void
     */
    protected function createGrid(ManialinkInterface $manialink)
    {
        $this->setdata($manialink, $this->fetchScriptSettings());

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($manialink->getData('dataCollection'))
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


        $apply = $this->uiFactory->createButton("expansion_admin.gui.window.scriptsettings.button.apply");
        $apply->setTranslate(true);
        $apply->setPosition(($frame->getWidth() - $apply->getWidth()), -($frame->getHeight() - $apply->getHeight() - 3));

        $apply->setAction(
            $this->actionFactory->createManialinkAction(
                $manialink,
                [$this, "callbackApply"],
                [],
                true
            )
        );


        $manialink->addChild($apply);


    }

    /** Callback for apply button
     *
     * @param $login
     * @param $entries
     * @param $args
     */
    public function callbackApply(ManialinkInterface $manialink, $login, $entries, $args)
    {
        /** @var GridBuilder $grid */
        $grid = $manialink->getData('grid');
        $grid->updateDataCollection($entries); // update datacollection

        // build settings array from datacollection
        $settings = [];
        foreach ($grid->getDataCollection()->getAll() as $key => $value) {
            $settings[$value['name']] = $value['value'];
        }

        try {
            $this->factory->getConnection()->setModeScriptSettings($settings);
            $this->closeManialink($manialink);

        } catch (\Exception $ex) {
            // TODO this should use chat notification.
            $this->factory->getConnection()->chatSendServerMessage("error: ".$ex->getMessage());
            $this->console->writeln('$f00Error: $fff'.$ex->getMessage());
        }
    }

    /**
     * helper function to fetch script settings
     */
    public function fetchScriptSettings()
    {
        $data = [];

        $scriptSettings = $this->factory->getConnection()->getModeScriptSettings();

        /**
         * @var string $i
         */
        $i = 1;
        foreach ($scriptSettings as $name => $value) {
            $data[] = [
                'index' => $i++,
                'name' => $name,
                'value' => $value,
            ];
        }

        return $data;
    }
}
