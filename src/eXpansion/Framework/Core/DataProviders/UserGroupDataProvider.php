<?php

namespace eXpansion\Framework\Core\DataProviders;

use eXpansion\Framework\Core\Model\UserGroups\Group;

/**
 * Class UserGroupDataProvider
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\DataProviders
 */
class UserGroupDataProvider extends AbstractDataProvider
{
    /**
     * When a user has been added to a user group.
     *
     * @param Group $group
     * @param $login
     */
    public function onExpansionGroupAddUser(Group $group, $login)
    {
        $this->dispatch(__FUNCTION__, [$group, $login]);
    }

    /**
     * When a user has been removed from a user group.
     *
     * @param Group $group
     * @param $login
     */
    public function onExpansionGroupRemoveUser(Group $group, $login)
    {
        $this->dispatch(__FUNCTION__, [$group, $login]);
    }

    /**
     * When a user group has been remvoed from a non persistent group, and the group will be destroyed.
     *
     * @param Group $group
     * @param $login
     */
    public function onExpansionGroupDestroy(Group $group, $login)
    {
        $this->dispatch(__FUNCTION__, [$group, $login]);
    }

}