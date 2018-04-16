<?php

namespace eXpansion\Framework\Config\Ui\Fields;

use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use FML\Types\Renderable;

/**
 * Class UiInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Config\Ui
 */
interface UiInterface
{
    /**
     * Create interface for the config value.
     *
     * @param ConfigInterface $config
     *
     * @return Renderable
     */
    public function build(
        ConfigInterface $config,
        $width,
        ManialinkInterface $manialink,
        ManialinkFactory $manialinkFactory
    ) : Renderable;

    /**
     * Check if Ui is compatible with the given config.
     *
     * @param ConfigInterface $config
     *
     * @return bool
     */
    public function isCompatible(ConfigInterface $config) : bool;

    /**
     * Get raw value to set from gui entry data.
     *
     * @param ConfigInterface $config
     * @param                 $entry
     *
     * @return mixed
     */
    public function getRawValueFromEntry(ConfigInterface $config, $entry);
}
