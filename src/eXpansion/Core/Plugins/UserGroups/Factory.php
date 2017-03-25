<?php

namespace eXpansion\Core\Plugins\UserGroups;

use eXpansion\Core\DataProviders\Listener\UserGroupDataListenerInterface;
use eXpansion\Core\Model\UserGroups\Group;
use eXpansion\Core\Services\Application\DispatcherInterface;

/**
 * Class Factory handles non persistent user groups.
 *
 * @package eXpansion\Core\Plugins
 * @author Oliver de Cramer
 */
class Factory implements UserGroupDataListenerInterface
{
    /** @var Group[] */
    protected $groups = [];

    /** @var string */
    protected $class = "";

    /** @var DispatcherInterface */
    protected $dispatcher;

    /**
     * IndividualUserGroups constructor.
     * @param string $class
     */
    public function __construct(DispatcherInterface $dispatcher, $class)
    {
        $this->class = $class;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get the individual group of a pleyer.
     *
     * @param $login
     *
     * @return Group
     */
    public function createForPlayer($login)
    {
        if (!isset($this->groups[$login])) {
            $class = $this->class;
            /** @var Group $group */
            $group = new $class($this->dispatcher);

            $this->groups[$login] = $group;
            $group->addLogin($login);
        }

        return $this->groups[$login];
    }

    /**
     * Create a group for
     *
     * @param Group
     */
    public function createForPlayers($logins)
    {
        $class = $this->class;
        /** @var Group $group */
        $group = new $class($this->dispatcher);

        $this->groups[$group->getName()] = $group;
        foreach ($logins as $login) {
            $group->addLogin($login);
        }

        return $group;
    }

    /**
     * Get a group from it's name if it exist.
     *
     * @param $groupName
     *
     * @return Group|null
     */
    public function getGroup($groupName)
    {
        return isset($this->groups[$groupName]) ? $this->groups[$groupName] : null;
    }

    /**
     * When a group is destyoed delete object.
     *
     * @param Group $group
     * @param $lastLogin
     */
    public function onExpansionGroupDestroy(Group $group, $lastLogin)
    {
        if (isset($this->groups[$group->getName()])) {
            unset($this->groups[$group->getName()]);
        }
    }

    public function onExpansionGroupAddUser(Group $group, $loginAdded)
    {
        // Nothing to
    }

    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved)
    {
        if (isset($this->groups[$loginRemoved])) {
            unset($this->groups[$loginRemoved]);
        }
    }
}