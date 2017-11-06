<?php

namespace eXpansion\Bundle\Admin\Plugins\Gui;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyManialink;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WindowFactoryContext;
use eXpansion\Framework\Core\Plugins\Gui\GridWindowFactory;
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
        Connection $connection
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->adminGroupsHelper = $adminGroupsHelper;
        $this->currentMenuView = Frame::create();
        $this->gridBuilderFactory = $gridBuilderFactory;
        $this->dataCollectionFactory = $dataCollectionFactory;
        $this->connection = $connection;
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

        $tooltip = $this->uiFactory->createTooltip();
        $manialink->addChild($tooltip);

        $queueButton = $this->uiFactory->createButton('Add', uiButton::TYPE_DECORATED);
        $queueButton->setTextColor("fff")->setSize(25, 5);
        $tooltip->addTooltip($queueButton, 'Add map to jukebox');

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn(
                'index',
                'expansion_admin.gui.window.column.index',
                1,
                true,
                false
            )->addTextColumn(
                'name',
                'expansion_admin.gui.window.column.name',
                5,
                true,
                false
            )->addInputColumn(
                'value',
                'expansion_admin.gui.window.column.author',
                3);

        $manialink->setData('grid', $gridBuilder);

    }

    public function callbackWish($login, $params, $args)
    {

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
