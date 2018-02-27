<?php

namespace eXpansion\Framework\Config\Ui\Fields;

use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Model\TextListConfig;
use eXpansion\Framework\Config\Ui\Window\ConfigWindowFactory;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ActionFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Types\Renderable;

/**
 * Class TextListField
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Ui\Fields
 */
class TextListField extends TextField
{
    /** @var ActionFactory */
    protected $actionFactory;

    /** @var ConfigWindowFactory */
    protected $configWindowFactory;

    /**
     * TextListField constructor.
     *
     * @param Factory $uiFactory
     * @param ActionFactory $actionFactory
     * @param ConfigWindowFactory $configWindowFactory
     */
    public function __construct(
        Factory $uiFactory,
        ActionFactory $actionFactory,
        ConfigWindowFactory $configWindowFactory
    ) {
        parent::__construct($uiFactory);

        $this->actionFactory = $actionFactory;
        $this->configWindowFactory = $configWindowFactory;
    }


    /**
     * Create interface for the config value.
     *
     * @param ConfigInterface $config
     *
     * @return Renderable
     */
    public function build(ConfigInterface $config, $width, ManialinkInterface $manialink): Renderable
    {
        $input = $this
            ->uiFactory
            ->createInput($config->getPath(), '')
            ->setWidth($width * 0.66);
        $addButton = $this->uiFactory
            ->createButton('Add')
            ->setWidth($width * 0.33)
            ->setAction(
                $this->actionFactory->createManialinkAction(
                    $manialink,
                    function (ManialinkInterface $manialink, $login, $entries, $args) {
                        /** @var TextListConfig $config */
                        $config = $args['config'];

                        if (!empty($entries[$config->getPath()])) {
                            $config->add(trim($entries[$config->getPath()]));

                            $this->configWindowFactory->update($manialink->getUserGroup());
                        }
                    },
                    ['config' => $config]
                )
            );

        $elements = [$this->uiFactory->createLayoutLine(0,0, [$input, $addButton])];
        foreach ($config->get() as $element) {
            $elements[] = $this->getElementLine($config, $manialink, $element, $width);
        }

        return $this->uiFactory->createLayoutRow(0, 0, $elements, 0.5);
    }

    /**
     * Get the display of a single line.
     *
     * @param $config
     * @param $manialink
     * @param $element
     * @param $width
     *
     * @return \eXpansion\Framework\Gui\Layouts\LayoutLine
     */
    protected function getElementLine($config, $manialink, $element, $width)
    {
        $label = $this->uiFactory
            ->createLabel($this->getElementName($element))
            ->setX(2)
            ->setWidth($width * 0.66);
        $delButton = $this->uiFactory
            ->createButton('Remove')
            ->setWidth($width * 0.33)
            ->setAction(
                $this->actionFactory->createManialinkAction(
                    $manialink,
                    function (ManialinkInterface $manialink, $login, $entries, $args) {
                        /** @var TextListConfig $config */
                        $config = $args['config'];
                        $config->remove($args['element']);

                        $this->configWindowFactory->update($manialink->getUserGroup());
                    },
                    ['config' => $config, 'element' => $element]
                )
            );

        return $this->uiFactory->createLayoutLine(0,0, [$label, $delButton]);
    }

    /**
     * Get the text to display for any element.
     *
     * @param $element
     *
     * @return string
     */
    protected function getElementName($element)
    {
        return $element;
    }

    /**
     * Check if Ui is compatible with the given config.
     *
     * @param ConfigInterface $config
     *
     * @return bool
     */
    public function isCompatible(ConfigInterface $config): bool
    {
        return $config instanceof TextListConfig;
    }
}
