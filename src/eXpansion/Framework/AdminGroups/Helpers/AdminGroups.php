<?php

namespace eXpansion\Framework\AdminGroups\Helpers;

use eXpansion\Framework\AdminGroups\Services\AdminGroupConfiguration;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory;

/**
 * Class AdminGroupConfiguration
 *
 * @package eXpansion\Bundle\AdminGroupConfiguration\Helpers;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class AdminGroups
{
    /** @var  AdminGroupConfiguration */
    protected $adminGroupConfiguration;

    /** @var  Factory */
    protected $userGroupFactory;

    /**
     * GroupsPlugin constructor.
     *
     * @param AdminGroupConfiguration $adminGroupConfiguration
     * @param Factory $userGroupFactory
     */
    public function __construct(
        AdminGroupConfiguration $adminGroupConfiguration,
        Factory $userGroupFactory
    ) {
        $this->adminGroupConfiguration = $adminGroupConfiguration;
        $this->userGroupFactory = $userGroupFactory;
    }

    /**
     * Get list of all user groups. This is usefull for gui actions.
     *
     * @return Group[]
     */
    public function getUserGroups()
    {
        $groups = [];
        foreach ($this->adminGroupConfiguration->getGroups() as $groupName) {
            $groups[] = $this->getUserGroup("$groupName");
        }

        return $groups;
    }

    /**
     * Get the group in which a user is. This is usefull for gui actions.
     *
     * @param $login
     *
     * @return Group
     */
    public function getLoginUserGroups($login)
    {
        $groupName = $this->adminGroupConfiguration->getLoginGroupName($login);
        if (empty($groupName)) {
            $groupName = 'guest';
        }

        return $this->getUserGroup("$groupName");
    }

    protected function getUserGroup($groupName)
    {
        $groupName = "admin:$groupName";

        $group = $this->userGroupFactory->getGroup($groupName);
        if (!$group) {
            $this->userGroupFactory->create($groupName);
            $group = $this->userGroupFactory->getGroup($groupName);
        }

        return $group;
    }

    /**
     * @param $login
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission($login, $permission)
    {
        return $this->adminGroupConfiguration->hasPermission($login, $permission);
    }
}
