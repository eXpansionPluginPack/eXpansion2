<?php

namespace eXpansion\Bundle\Players\Plugins\Gui;

/**
 * Class IgnoreListWindow
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Bundle\Players\Plugins\Gui
 */
class BanListWindow extends AbstractListWindow
{
    /**
     * @inheritdoc
     */
    function getDataSet(): array
    {
        return $this->factory->getConnection()->getBanList();
    }

    /**
     * @inheritdoc
     */
    function executeForPlayer($login)
    {
        $this->factory->getConnection()->unBan($login);
    }
}