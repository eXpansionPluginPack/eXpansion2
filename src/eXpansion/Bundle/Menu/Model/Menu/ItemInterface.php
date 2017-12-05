<?php

namespace eXpansion\Bundle\Menu\Model\Menu;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use FML\Controls\Quad;

/**
 * Interface ItemInterface
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2017 eXpansion
 * @package eXpansion\Bundle\Menu\Model\Menu
 */
interface ItemInterface
{
    /**
     * Unique identifier for menu item (needs to be unique in level)
     *
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getPath();

    /**
     * Label to be translated to be displayed on the menu.
     *
     * @return string
     */
    public function getLabelId();
    /**
     * Get the permission required to use this menu item.
     *
     * @return mixed
     */
    public function permission();

    /**
     * @param ManialinkFactory $manialinkFactory
     * @param ManialinkInterface $manialink
     * @param $login
     * @param $answerValues
     * @param $args
     *
     * @return mixed
     */
    public function execute(ManialinkFactory $manialinkFactory, ManialinkInterface $manialink, $login, $answerValues, $args);

    /**
     * Check if item is visible for a certain group.
     *
     * @param string $login
     *
     * @return mixed
     */
    public function isVisibleFor($login);
}