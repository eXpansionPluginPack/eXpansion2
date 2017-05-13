<?php

namespace eXpansion\Framework\Core\Model\Gui;

use eXpansion\Framework\Core\Model\UserGroups\Group;

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
