<?php

namespace eXpansion\Framework\AdminGroups\Services;

/**
 * Class AdminGroupConfiguration
 *
 * @package eXpansion\Bundle\AdminGroupConfiguration\Services;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class AdminGroupConfiguration
{
    protected $config;

    protected $loginGroups = [];

    /**
     * AdminGroupConfiguration constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        foreach ($this->config as $groupName => $groupData)
        {
            if (!empty($groupData['logins'])) {
                foreach ($groupData['logins'] as $login) {
                    $this->loginGroups[$login] = $groupName;
                }
            }
        }
    }

    /**
     * Get the list of all admin groups.
     *
     * @return string[]
     */
    public function getGroups()
    {
        return array_keys($this->config);
    }

    /**
     * Get list of all users in a group (not just connected).
     *
     * @param $groupName
     *
     * @return string[]
     */
    public function getGroupLogins($groupName)
    {
        if (!isset($this->config[$groupName])) {
            return null;
        }

        return $this->config[$groupName]['logins'];
    }

    /**
     * Get list of all permissions given to a group.
     *
     * @param $groupName
     *
     * @return string[]
     */
    public function getGroupPermissions($groupName)
    {
        if (!isset($this->config[$groupName])) {
            return [];
        }

        return $this->config[$groupName]['permissions'];
    }

    /**
     * @param string $login
     *
     * @return string
     */
    public function getLoginGroupName($login)
    {
        return isset($this->loginGroups[$login]) ? $this->loginGroups[$login] : null;
    }

    /**
     * @param $login
     * @param $permission
     *
     * @return bool
     */
    public function hasPermission($login, $permission)
    {
        $groupName = $this->getLoginGroupName($login);

        echo "\n\n$groupName\n\n";

        if ($groupName != 'master_admin') {
            $permissions = $this->getGroupPermissions($groupName);
            return in_array($permission, $permissions);
        } else {
            return true;
        }
    }
}
