<?php

namespace eXpansion\Framework\Config\Ui\Fields;
use eXpansion\Framework\Config\Model\ConfigInterface;
use FML\Controls\Frame;

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
     * @return Frame
     */
    public function build(ConfigInterface $config, $width) : Frame;

    /**
     * Check if Ui is compatible with the given config.
     *
     * @param ConfigInterface $config
     *
     * @return bool
     */
    public function isCompatible(ConfigInterface $config) : bool;
}
