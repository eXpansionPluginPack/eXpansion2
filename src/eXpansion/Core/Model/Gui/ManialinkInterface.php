<?php

namespace eXpansion\Core\Model\Gui;

use eXpansion\Core\Model\UserGroups\Group;

interface ManialinkInterface
{
    /**
     *
     * @return string
     */
    public function getXml();

    /**
     *
     * @return Group
     */
    public function getUserGroup();

    /**
     *
     * @return string
     */
    public function getId();
}
