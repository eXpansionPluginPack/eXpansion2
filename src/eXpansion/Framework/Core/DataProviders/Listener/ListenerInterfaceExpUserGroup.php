<?php

namespace eXpansion\Framework\Core\DataProviders\Listener;

use eXpansion\Framework\Core\Model\UserGroups\Group;

/**
 * Class ListenerInterfaceExpUserGroup
 *
 * @author    de Cramer Oliver<oldec@smile.fr>
 * @copyright 2017 Smile
 * @package eXpansion\Framework\Core\DataProviders\Listener
 */
interface ListenerInterfaceExpUserGroup
{
    public function onExpansionGroupAddUser(Group $group, $loginAdded);

    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved);

    public function onExpansionGroupDestroy(Group $group, $lastLogin);
}
