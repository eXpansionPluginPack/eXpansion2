<?php

namespace eXpansion\Framework\Core\Plugins\Gui;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryContext;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use Oliverde8\PageCompose\Service\BlockDefinitions;
use Oliverde8\PageCompose\Service\UiComponents;

/**
 * Class MlBuilderFactory
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\Core\Plugins\Gui
 */
class MlBuilderFactory extends FmlManialinkFactory
{
    protected $guiBuilderId;
    protected $blockDefinitions;
    protected $uiComponents;

    public function __construct(
        $name,
        $guiBuilderId,
        $sizeX,
        $sizeY,
        $posX = null,
        $posY = null,
        ManialinkFactoryContext $context,
        BlockDefinitions $blockDefinitions,
        UiComponents $uiComponents
    ) {
        parent::__construct($name, $sizeX, $sizeY, $posX, $posY, $context);
        $this->guiBuilderId = $guiBuilderId;
        $this->blockDefinitions = $blockDefinitions;
        $this->uiComponents = $uiComponents;
    }

    /**
     * @inheritdoc
     */
    protected function createContent(ManialinkInterface $manialink)
    {
        parent::createContent($manialink);

        $manialink->setData('guiBlock', $this->blockDefinitions->getBlock($this->guiBuilderId, []));
    }

    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
        $manialink->getContentFrame()->removeAllChildren();
        $pageBlock = $manialink->getData("guiBlock");

        // Prepare all blocks and wait for them to be ready.
        $promise = $this->uiComponents->prepare($pageBlock, []);
        $promise->resolve("");

        // Display the content.
        $manialink->getContentFrame()->addChild($this->uiComponents->display($pageBlock, $this, $manialink));
    }
}