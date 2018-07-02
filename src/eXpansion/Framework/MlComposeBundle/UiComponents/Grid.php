<?php

namespace eXpansion\Framework\MlComposeBundle\UiComponents;

use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Layouts\LayoutLine;
use eXpansion\Framework\Gui\Layouts\LayoutRow;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use Oliverde8\PageCompose\Block\BlockDefinition;
use Oliverde8\PageCompose\Block\BlockDefinitionInterface;
use Oliverde8\PageCompose\Service\UiComponents;

/**
 * Class Grid
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\UiComponents
 */
class Grid extends FmlComponent
{
    public function __construct(UiComponents $uiComponents, ActionFactory $actionFactory)
    {
        parent::__construct($uiComponents, $actionFactory, LayoutRow::class);
    }

    /**
     * Prepare the component.
     *
     * @param BlockDefinitionInterface $blockDefinition
     * @param AssociativeArray $context
     * @param array $args
     *
     * @return string
     * @throws \Exception
     */
    public function prepare(BlockDefinitionInterface $blockDefinition, $context, ...$args)
    {
        /** @var ManialinkInterface $manialink */
        $manialink = $context->get('ml');

        // Prepare default value for pager.
        $page = $manialink->getData("{$blockDefinition->getUniqueKey()}--page");
        if (!$page) {
            $manialink->setData("{$blockDefinition->getUniqueKey()}--page", 1);
        }

        return parent::prepare($blockDefinition, $args);
    }


    /**
     * Display the component.
     *
     * @param BlockDefinitionInterface $blockDefinition
     * @param AssociativeArray $context
     * @param array $args
     *
     * @return string
     * @throws \Exception
     */
    public function display(BlockDefinitionInterface $blockDefinition, $context, ...$args)
    {
        /** @var LayoutRow $frame */
        $frame = parent::display($blockDefinition, ...$args);
        $configuration = new AssociativeArray($blockDefinition->getConfiguration());

        /** @var DataCollectionInterface $dataSource */
        $dataSource = clone $blockDefinition->getConfiguration()['data-source'];
        $dataSource->setPageSize($configuration->get('page-size', 10));

        /** @var ManialinkInterface $manialink */
        $manialink = $context->get('ml');
        $page = $manialink->getData("{$blockDefinition->getUniqueKey()}--page");

        if ($configuration->get('layout', 'table') == 'table') {
            $totalWidth = $frame->getWidth();
            $columnWidths = $this->getColumnWidths($totalWidth, $this->getColumnBlocks($blockDefinition));

            // Generate the header.
            $header = new LayoutLine(0, 0);
            foreach ($this->getColumnBlocks($blockDefinition) as $alias => $block) {
                $titleBlock = $blockDefinition->getSubBlocks()['title'];
                $title = $this->uiComponents->display(
                    $this->createNewDefinition($titleBlock, $columnWidths[$alias], $block->getConfiguration()['title'])
                );

                $header->addChild($title);
            }
            $frame->addChild($header);

            // Generate other lines.
            foreach ($dataSource->getData($page) as $data) {
                $line = new LayoutLine(0, 0);
                foreach ($this->getColumnBlocks($blockDefinition) as $alias => $block) {
                    $line->addChild(
                        $this->uiComponents->display($blockDefinition, $columnWidths[$alias], $data)
                    );
                }
            }
        }
    }

    /**
     * Get width for each column.
     *
     * @TODO Separate in dedicated helper service the logic.
     *
     * @param $totalWidth
     * @param BlockDefinitionInterface[] $blocDefinitions
     *
     * @return float[]
     */
    protected function getColumnWidths($totalWidth, $blocDefinitions)
    {
        $columnWidths = [];
        $totalCoefs = 0;
        foreach ($blocDefinitions as $column) {
            $totalCoefs += AssociativeArray::getFromKey($column->getConfiguration(), 'width');
        }

        $multiplier = $totalWidth / $totalCoefs;
        foreach ($blocDefinitions as $column) {
            $columnWidths[$column->getUniqueKey()] = AssociativeArray::getFromKey($column->getConfiguration(), 'width') * $multiplier;
        }

        return $columnWidths;
    }

    /**
     * Get column blocks.
     *
     * @param BlockDefinitionInterface $blockDefinition
     *
     * @return BlockDefinitionInterface[]
     */
    protected function getColumnBlocks(BlockDefinitionInterface $blockDefinition) {
        foreach ($blockDefinition->getSubBlocks() as $alias => $blockDefinition) {
            if (strpos($alias, 'column_') === 0) {
                yield $blockDefinition;
            }
        }
    }

    /**
     * @TODO move this to dedicated bloc definition factory service.
     *
     * @param BlockDefinitionInterface $oldDefinition
     * @param $width
     * @param null $text
     *
     * @return BlockDefinition
     */
    protected function createNewDefinition(BlockDefinitionInterface $oldDefinition, $width, $text = null)
    {
        $configuration = $oldDefinition->getConfiguration();
        $configuration['width'] = $width;
        if ($text) {
            $configuration['text'] = $text;
        }

        return new BlockDefinition(
            $oldDefinition->getUniqueKey(),
            $oldDefinition->getUiComponentName(),
            $oldDefinition->getParentKey(),
            $oldDefinition->getSubBlocks(),
            $configuration,
            $oldDefinition->getGlobalConfiguration()
        );
    }
}