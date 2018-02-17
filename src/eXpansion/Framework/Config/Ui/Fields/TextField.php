<?php

namespace eXpansion\Framework\Config\Ui\Fields;

use eXpansion\Framework\Config\Model\AbstractConfig;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Types\Renderable;

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
    public function build(ConfigInterface $config, $width): Renderable
    {
        return $this
            ->uiFactory
            ->createInput($config->getPath(), $config->getRawValue())
            ->setWidth($width);
    }

    /**
     * @inheritdoc
     */
    public function isCompatible(ConfigInterface $config): bool
    {
        return ($config instanceof AbstractConfig);
    }
}
