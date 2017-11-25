<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;

/**
 * Interface GuiHandlerInterface
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\Plugins
 */
interface GuiHandlerInterface
{
    public function addToDisplay(ManialinkInterface $manialink, ManialinkFactory $manialinkFactory);

    public function addToHide(ManialinkInterface $manialink, ManialinkFactory $manialinkFactory);

    /**
     * Get manialink for a group and manialink factory.
     *
     * @param Group            $group
     * @param ManialinkFactory $manialinkFactory
     *
     * @return null
     */
    public function getManialink(Group $group, ManialinkFactory $manialinkFactory);
}
