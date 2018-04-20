<?php

namespace eXpansion\Framework\MlComposeBundle\UiComponents;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use FML\Controls\Control;
use FML\Controls\Frame;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use Oliverde8\PageCompose\Block\BlockDefinitionInterface;
use Oliverde8\PageCompose\Service\UiComponents;
use Oliverde8\PageCompose\UiComponent\AbstractUiComponent;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class BaseFmlComponent
 *
 * @author    de Cramer Oliver<oiverde8@gmail.com>
 * @copyright 2018 Oliverde8
 * @package eXpansion\Framework\MlComposeBundle\UiComponents
 */
class  FmlComponent extends AbstractUiComponent
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;
    protected $fmlClass;

    public function __construct(UiComponents $uiComponents, ActionFactory $actionFactory, $fmlClass)
    {
        parent::__construct($uiComponents);
        $this->propertAccess = PropertyAccess::createPropertyAccessor();
        $this->actionFactory = $actionFactory;
        $this->fmlClass = $fmlClass;
    }

    /**
     * Display the component.
     *
     * @param BlockDefinitionInterface $blockDefinition
     * @param array ...$args
     *
     * @return string
     */
    public function display(BlockDefinitionInterface $blockDefinition, ...$args)
    {
        $expressionLanguage = new ExpressionLanguage();
        $configuration = new AssociativeArray($blockDefinition->getConfiguration());
        $class = $this->fmlClass;

        $arguments = [
            'factory' => $args[0],
            'manialink' => $args[1],
            'args' => $args,
        ];

        /** @var Control  $component */
        $component = new $class($configuration->get('id', $blockDefinition->getUniqueKey()));

        foreach ($blockDefinition->getSubBlocks() as $block) {
            /** @var Frame  $component */
            $component->addChild($this->uiComponents->display($block, ...$args));
        }

        foreach ($configuration->get('expr', []) as $key => $value) {
            $function = ucwords("set$key");
            $component->$function($expressionLanguage->evaluate($value, $arguments));
        }

        foreach ($configuration->get('args', []) as $key => $value) {
            $function = ucwords("set$key");
            $component->$function(...$value);
        }

        foreach ($configuration->get('def', []) as $key => $value) {
            $function = ucwords("set$key");
            $component->$function($value);
        }

        if ($args[1] instanceof ManialinkInterface) {
            if ($configuration->get('action')) {
                $callback = [$configuration->get('action/0/service', $args[0]), $configuration->get('action/0/method')];
                $this->actionFactory->createManialinkAction($args[1], $callback, [], false);
            }
        } else {
            // Log warning!
        }

        return $component;
    }
}
