<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\DataProviders\ChatCommandDataProvider;
use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Services\ChatCommands;
use FML\Controls\Frame;
use FML\Controls\Label;
use FML\Controls\Quads\Quad_Icons64x64_1;


/**
 * Class HelpFactory
 *
 * @package eXpansion\Framework\AdminGroups\Plugins\Window;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WindowHelpFactory extends WindowFactory
{
    /** @var GridBuilderFactory */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory */
    protected $dataCollectionFactory;

    /** @var ChatCommands */
    protected $chatCommands;

    /** @var ChatCommandDataProvider */
    protected $chatCommandDataPovider;

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
     * @param ChatCommands $chatCommands
     */
    public function setChatCommands($chatCommands)
    {
        $this->chatCommands = $chatCommands;
    }

    public function setChatCommandDataProvide($chatCommandDataPovider)
    {
        $this->chatCommandDataPovider = $chatCommandDataPovider;
    }

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $collection = $this->dataCollectionFactory->create($this->chatCommands->getChatCommands());
        $collection->setPageSize(2);

        $helpButton = new Label();
        $helpButton->setText('')
            ->setSize(6, 6)
            ->setAreaColor("0000")
            ->setAreaFocusColor("0000");

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addTextColumn('command', "Command", 25)
            ->addTextColumn('description', 'Description', 70)
            ->addActionColumn('help', '', 5, array($this, 'callbackHelp'), $helpButton);

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

        $collection = $this->dataCollectionFactory->create($this->chatCommands->getChatCommands());
        $collection->setPageSize(20);

        /** @var GridBuilder $gridBuilder */
        $gridBuilder = $manialink->getData('grid');
        $contentFrame->addChild($gridBuilder->build($contentFrame->getWidth(), $contentFrame->getHeight()));
    }

    /**
     * Callbacked called when help button is pressed.
     *
     * @param $login
     * @param $params
     * @param $arguments
     */
    public function callbackHelp($login, $params, $arguments)
    {
        $this->chatCommandDataPovider->onPlayerChat(0, $login, '/' . $arguments['command'] . ' -h', true);
    }
}
