<?php

namespace eXpansion\Framework\Config\Ui;

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
     * @inheritdoc
     */
    public function build(ConfigInterface $config, $width): Frame
    {
        $frame = new Frame();

        $descriptionButton = $this->uiFactory->createButton("?");
        $frame->addChild(
            $this->uiFactory->createLayoutRow(
                0,
                0,
                [
                    $this->uiFactory->createLabel($config->getName(), ($width * 2) / 3),
                    $this->uiFactory->createInput($config->getPath(), ($width * 2) / 3),
                    $descriptionButton,
                ]
            )
        );

        $tooltip = $this->uiFactory->createTooltip();
        $frame->addChild($tooltip);

        $tooltip->addTooltip($descriptionButton, $config->getDescription());
    }

    /**
     * @inheritdoc
     */
    public function isCompatible(ConfigInterface $config): bool
    {
        return ($config instanceof AbstractConfig);
    }
}
