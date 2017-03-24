<?php

namespace eXpansion\Core\DataProviders\Listener;

use eXpansion\Core\Model\UserGroups\Group;

/**
 * Class UserGroupDataListenerInterface
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Core\DataProviders\Listener
 */
interface UserGroupDataListenerInterface
{
    public function onExpansionGroupAddUser(Group $group, $loginAdded);

    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved);

    public function onExpansionGroupDestroy(Group $group, $lastLogin);
}
