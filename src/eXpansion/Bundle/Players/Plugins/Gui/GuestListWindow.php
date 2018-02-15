<?php

namespace eXpansion\Bundle\Players\Plugins\Gui;

/**
 * Class IgnoreListWindow
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Bundle\Players\Plugins\Gui
 */
class GuestListWindow extends AbstractListWindow
{
    /**
     * @inheritdoc
     */
    function getDataSet(): array
    {
        return $this->connection->getGuestList();
    }

    /**
     * @inheritdoc
     */
    function executeForPlayer($login)
    {
        $this->connection->removeGuest($login);
    }
}