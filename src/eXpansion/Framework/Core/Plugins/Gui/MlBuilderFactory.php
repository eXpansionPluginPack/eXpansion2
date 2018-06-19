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

        $manialink->setData('guiBlocks', $this->blockDefinitions->getPageBlocks($this->guiBuilderId, []));
    }

    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        parent::updateContent($manialink);
        $manialink->getContentFrame()->removeAllChildren();
        $pageBlocks = $manialink->getData("guiBlocks");

        // Prepare all blocks and wiat for them to be ready.
        $promises = [];
        foreach ($pageBlocks as $blockDefinition) {
            $promises[] = $this->uiComponents->prepare($blockDefinition, []);
        }


        $promise = \GuzzleHttp\Promise\all($promises);
        var_dump($promise->getState());
        $promise->resolve("");

        // Display the content.
        foreach ($pageBlocks as $blockDefinition) {
            $manialink->getContentFrame()->addChild($this->uiComponents->display($blockDefinition, $this, $manialink));
        }
    }
}