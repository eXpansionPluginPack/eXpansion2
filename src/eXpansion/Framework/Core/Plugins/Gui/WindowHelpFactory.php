<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionFactory;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilder;
use eXpansion\Framework\Core\Model\Gui\Grid\GridBuilderFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Services\ChatCommands;
use FML\Controls\Frame;


/**
 * Class HelpFactory
 *
 * @package eXpansion\Framework\AdminGroups\Plugins\Window;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class WindowHelpFactory extends WindowFactory
{
    /** @var GridBuilderFactory  */
    protected $gridBuilderFactory;

    /** @var DataCollectionFactory  */
    protected $dataCollectionFactory;

    /** @var ChatCommands  */
    protected $chatCommands;

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

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        $collection = $this->dataCollectionFactory->create($this->chatCommands->getChatCommands());
        $collection->setPageSize(2);

        $gridBuilder = $this->gridBuilderFactory->create();
        $gridBuilder->setManialink($manialink)
            ->setDataCollection($collection)
            ->setManialinkFactory($this)
            ->addColumn('command', "Command", 25)
            ->addColumn('description', 'Description', 75);

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
}