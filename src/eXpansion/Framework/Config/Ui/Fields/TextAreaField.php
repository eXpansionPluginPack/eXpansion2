<?php

namespace eXpansion\Framework\Config\Ui\Fields;

use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Types\Renderable;

/**
 * Class TextAreaField
 *
 * @author    reaby
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Ui
 */
class TextAreaField implements UiInterface
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
    public function build(ConfigInterface $config, $width, ManialinkInterface $manialink, ManialinkFactory $manialinkFactory): Renderable
    {
        return $this
            ->uiFactory
            ->createTextbox($config->getPath(), $config->getRawValue(), 5, $width);
    }

    /**
     * @inheritdoc
     */
    public function isCompatible(ConfigInterface $config): bool
    {
        return ($config instanceof TextAreaField);
    }

    /**
     * Get raw value to set from gui entry data.
     *
     * @param ConfigInterface $config
     * @param                 $entry
     *
     * @return mixed
     */
    public function getRawValueFromEntry(ConfigInterface $config, $entry)
    {
        return $entry;
    }
}
