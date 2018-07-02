<?php

namespace eXpansion\Framework\MlComposeBundle\Helpers;

use Oliverde8\PageCompose\Block\BlockDefinition;
use Oliverde8\PageCompose\Block\BlockDefinitionInterface;

/**
 * Class BlockDefinitionHelper
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\Helpers
 */
class BlockDefinitionHelper
{
    /**
     * Create new block by overriding configuration of original block.
     *
     * @param BlockDefinitionInterface $oldDefinition
     * @param $configuration
     *
     * @return BlockDefinition
     */
    public function createNewDefinition(BlockDefinitionInterface $oldDefinition, $configuration)
    {
        $configuration = $oldDefinition->getConfiguration() + $configuration;

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