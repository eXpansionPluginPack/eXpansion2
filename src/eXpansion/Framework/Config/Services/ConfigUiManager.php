<?php

namespace eXpansion\Framework\Config\Services;

use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Ui\Fields\UiInterface;

/**
 * Class ConfigUiManager
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Framework\Config\Services
 */
class ConfigUiManager
{
    /** @var UiInterface[] */
    protected $uiHandlers = [];

    /**
     * Register an ui handler for configurations.
     *
     * @param UiInterface $ui
     */
    public function registerUi(UiInterface $ui)
    {
        $this->uiHandlers[] = $ui;
    }

    /**
     * Get proper handler to generate ui for a config element.
     *
     * @param ConfigInterface $config
     *
     * @return UiInterface|null
     */
    public function getUiHandler(ConfigInterface $config)
    {
        if ($config->isHidden()) {
            return null;
        }

        foreach ($this->uiHandlers as $ui) {
            if ($ui->isCompatible($config)) {
                return $ui;
            }
        }

        return null;
    }
}