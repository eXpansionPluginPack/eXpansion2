<?php

namespace eXpansion\Bundle\Players\Plugins\Gui;

/**
 * Class IgnoreListWindow
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2018 Smile
 * @package eXpansion\Bundle\Players\Plugins\Gui
 */
class IgnoreListWindow extends AbstractListWindow
{
    /**
     * @inheritdoc
     */
    function getDataSet(): array
    {
        return $this->factory->getConnection()->getIgnoreList();
    }

    /**
     * @inheritdoc
     */
    function executeForPlayer($login)
    {
        $this->factory->getConnection()->unIgnore($login);
    }
}