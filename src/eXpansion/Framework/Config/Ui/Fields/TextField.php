<?php

namespace eXpansion\Framework\Config\Ui\Fields;

use eXpansion\Framework\Config\Model\AbstractConfig;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Controls\Frame;

/**
 * Class TextField
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Ui
 */
class TextField implements UiInterface
{
    /** @var Factory */
    protected $uiFactory;

    /**
     * TextField constructor.
     *
     * @param Factory $uiFactory
     */
    public function __construct(Factory $uiFactory)
    {
        $this->uiFactory = $uiFactory;
    }

    /**
     * @inheritdoc
     */
    public function build(ConfigInterface $config, $width): Frame
    {
        $frame = new Frame();

        $descriptionButton = $this->uiFactory->createButton("?");
        $rowLayout =  $this->uiFactory->createLayoutLine(
            0,
            0,
            [
                $this->uiFactory->createLabel($config->getName(), ($width * 2) / 3),
                $this->uiFactory->createInput($config->getPath(), ($width * 2) / 3)->setDefault($config->getRawValue()),
                $descriptionButton,
            ]
        );

        $frame->addChild($rowLayout);
        $frame->setSize($width, $rowLayout->getHeight());

        $tooltip = $this->uiFactory->createTooltip();
        $frame->addChild($tooltip);

        $tooltip->addTooltip($descriptionButton, $config->getDescription());
        return $frame;
    }

    /**
     * @inheritdoc
     */
    public function isCompatible(ConfigInterface $config): bool
    {
        return ($config instanceof AbstractConfig);
    }
}
