<?php

namespace eXpansion\Framework\MlComposeBundle\UiComponents;

use eXpansion\Framework\Core\Model\Gui\Grid\DataCollectionInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Layouts\LayoutLine;
use eXpansion\Framework\Gui\Layouts\LayoutRow;
use eXpansion\Framework\MlComposeBundle\Helpers\BlockDefinitionHelper;
use eXpansion\Framework\MlComposeBundle\Helpers\GridHelper;
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
    /** @var GridHelper  */
    protected $gridHelper;

    /** @var BlockDefinitionHelper */
    protected $blockDefinitionHelper;

    /**
     * Grid constructor.
     *
     * @param UiComponents $uiComponents
     * @param ActionFactory $actionFactory
     * @param GridHelper $gridHelper
     * @param BlockDefinitionHelper $blockDefinitionHelper
     */
    public function __construct(
        UiComponents $uiComponents,
        ActionFactory $actionFactory,
        GridHelper $gridHelper,
        BlockDefinitionHelper $blockDefinitionHelper
    ){
        parent::__construct($uiComponents, $actionFactory, LayoutRow::class);
        $this->gridHelper = $gridHelper;
        $this->blockDefinitionHelper = $blockDefinitionHelper;
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
        $frame = parent::display($blockDefinition, ...$args); // TODO this also display the columns all wrong. Needs fixing.
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
                    $subConf = $block->getConfiguration();
                    $line->addChild(
                        $this->uiComponents->display(
                            $blockDefinition,
                            $context,
                            $columnWidths[$alias],
                            !empty($subConf['key']) ? $dataSource->getLineData($data, $subConf['key']) : $data,
                            $args
                        )
                    );
                }
            }
        }

        return $frame;
    }

    /**
     * Get width for each column.
     *
     * @param $totalWidth
     * @param BlockDefinitionInterface[] $blocDefinitions
     *
     * @return float[]
     */
    protected function getColumnWidths($totalWidth, $blocDefinitions)
    {
        array_walk(
            $blocDefinitions,
            function(&$value, $key) {
                $value = AssociativeArray::getFromKey($value->getConfiguration(), 'width');
            }
        );

        return $this->gridHelper->getNormalizedWidths($totalWidth, $blocDefinitions);
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
     * Create a new bloc definition with new width and possibly new text.
     *
     * @param BlockDefinitionInterface $oldDefinition
     * @param float $width
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

        return $this->blockDefinitionHelper->createNewDefinition($oldDefinition, $configuration);
    }
}
