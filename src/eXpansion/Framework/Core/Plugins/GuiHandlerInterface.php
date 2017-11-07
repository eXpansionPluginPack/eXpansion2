<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;

/**
 * Interface GuiHandlerInterface
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Plugins
 */
interface GuiHandlerInterface
{
    public function addToDisplay(ManialinkInterface $manialink);

    public function addToHide(ManialinkInterface $manialink);
}
