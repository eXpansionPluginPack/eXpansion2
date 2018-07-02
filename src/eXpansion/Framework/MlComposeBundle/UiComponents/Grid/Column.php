<?php

namespace eXpansion\Framework\MlComposeBundle\UiComponents\Grid;

use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\MlComposeBundle\Helpers\BlockDefinitionHelper;
use eXpansion\Framework\MlComposeBundle\UiComponents\FmlComponent;
use FML\Controls\Frame;
use Oliverde8\PageCompose\Block\BlockDefinitionInterface;
use Oliverde8\PageCompose\Service\UiComponents;

/**
 * Class Column
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\UiComponents\Grid
 */
class Column extends FmlComponent
{
    /** @var BlockDefinitionHelper */
    protected $blockDefinitionHelper;

    public function __construct(UiComponents $uiComponents, ActionFactory $actionFactory)
    {
        parent::__construct($uiComponents, $actionFactory, Frame::class);
    }

    public function display(BlockDefinitionInterface $blockDefinition, $context, ...$args)
    {
        $width = $args[0];
        $data = $args[1];
        $frame = parent::display($blockDefinition, $context, $args);

        return $frame;
    }


}