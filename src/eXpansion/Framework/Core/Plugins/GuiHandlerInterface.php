<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\Model\Gui\Manialink;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryInterface;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;

/**
 * Interface GuiHandlerInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Framework\Core\Plugins
 */
interface GuiHandlerInterface
{
    /**
     * Add a manialink to the display.
     *
     * @param ManialinkInterface $manialink
     *
     * @return void
     */
    public function addToDisplay(ManialinkInterface $manialink);

    /**
     * Hide a manialink.
     *
     * @param ManialinkInterface $manialink
     *
     * @return void
     */
    public function addToHide(ManialinkInterface $manialink);

    /**
     * Get manialink for a group and manialink factory.
     *
     * @param Group                     $group
     * @param ManialinkFactoryInterface $manialinkFactory
     *
     * @return null|ManialinkInterface
     */
    public function getManialink(Group $group, ManialinkFactoryInterface $manialinkFactory);

    /**
     * Get all manialinks created by a manialink factory.
     *
     * @param ManialinkFactory $manialinkFactory
     *
     * @return Manialink[]
     */
    public function getFactoryManialinks(ManialinkFactory $manialinkFactory);
}
