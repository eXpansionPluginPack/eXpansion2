<?php

namespace eXpansion\Framework\Config\Ui\Fields;

use eXpansion\Framework\Config\Model\BooleanConfig;
use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Framework\Gui\Ui\Factory;
use FML\Types\Renderable;

/**
 * Class TextField
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Ui
 */
class BooleanField implements UiInterface
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

        if ($config->get()) {
            $selectedIndex = 0;
        } else {
            $selectedIndex = 1;
        }

        return $this
            ->uiFactory
            ->createDropdown($config->getPath(), ["True" => "true", "False" => "false"], $selectedIndex, false)
            ->setWidth($width);
    }

    /**
     * @inheritdoc
     */
    public function isCompatible(ConfigInterface $config): bool
    {
        return ($config instanceof BooleanConfig);
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
