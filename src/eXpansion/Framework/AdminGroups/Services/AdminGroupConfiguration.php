<?php

namespace eXpansion\Framework\AdminGroups\Services;

use eXpansion\Framework\Config\Model\ConfigInterface;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;

/**
 * Class AdminGroupConfiguration
 *
 * @package eXpansion\Bundle\AdminGroupConfiguration\Services;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class AdminGroupConfiguration
{
    /** @var ConfigInterface[][] */
    protected $config;

    /**
     * AdminGroupConfiguration constructor.
     *
     * @param ConfigManagerInterface $configManager
     * @param string $path
     */
    public function __construct(ConfigManagerInterface $configManager, $path)
    {
        foreach ($configManager->getConfigDefinitionTree()->get($path) as $groupName => $data) {
            foreach ($data as $key => $config) {
                if (strpos($key, 'perm_') == 3) {
                    $this->config[$groupName]['permissions'][str_replace('perm_', '', $key)] = $config;
                } else {
                    $this->config[$groupName]['permissions'][$key] = $config;
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
     * @return string[]|null
     */
    public function getGroupLogins($groupName)
    {
        if (!isset($this->config[$groupName])) {
            return null;
        }

        return $this->config[$groupName]['logins']->getRawValue();
    }

    /**
     * Get list of all permissions given to a group.
     *
     * @param string $groupName
     *
     * @return string[]
     */
    public function getGroupPermissions($groupName)
    {
        if (!isset($this->config[$groupName])) {
            return [];
        }

        return $this->config[$groupName]['permissions']->getRawValue();
    }

    /**
     * Get admin group label
     *
     * @param string $groupName
     * @return string
     */
    public function getGroupLabel($groupName)
    {
        if (!isset($this->config[$groupName])) {
            return "";
        }

        return $this->config[$groupName]['label']->get();
    }


    /**
     * @param string $login
     *
     * @return string|null
     */
    public function getLoginGroupName($login)
    {
        foreach ($this->config as $groupName => $group) {
            if (in_array($login, $group['logins']->get())) {
                return $groupName;
            }
        }

        return null;
    }

    /**
     * @param string $login
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission($login, $permission)
    {
        $groupName = $this->getLoginGroupName($login);

        // if login has no groups, no permission
        if ($groupName === null) {
            return false;
        }
        // master admin has all permissions
        if ($groupName == 'master_admin') {
            return true;
        }

        $permissions = $this->getGroupPermissions($groupName);

        return in_array($permission, $permissions);
    }
}
