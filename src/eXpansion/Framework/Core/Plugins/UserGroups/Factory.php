<?php

namespace eXpansion\Framework\Core\Plugins\UserGroups;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpUserGroup;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;
use eXpansion\Framework\Core\Storage\Data\Player;

/**
 * Class Factory handles non persistent user groups.
 *
 * @package eXpansion\Framework\Core\Plugins
 * @author Oliver de Cramer
 */
class Factory implements ListenerInterfaceExpUserGroup, ListenerInterfaceMpLegacyPlayer
{
    /** @var Group[] */
    protected $groups = [];

    /** @var string */
    protected $class = "";

    /** @var DispatcherInterface */
    protected $dispatcher;

    /**
     * IndividualUserGroups constructor.
     *
     * @param string $class
     * @param DispatcherInterface $dispatcher
     */
    public function __construct($class, DispatcherInterface $dispatcher)
    {
        $this->class = $class;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Get the individual group of a player.
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
            $group = new $class(null, $this->dispatcher);

            $this->groups[$login] = $group;
            $group->addLogin($login);
        }

        return $this->groups[$login];
    }

    /**
     * Create a group for
     *
     * @param string[]
     *
     * @return Group
     */
    public function createForPlayers($logins)
    {
        $class = $this->class;
        /** @var Group $group */
        $group = new $class(null, $this->dispatcher);

        $this->groups[$group->getName()] = $group;
        foreach ($logins as $login) {
            $group->addLogin($login);
        }

        return $group;
    }

    /**
     * Create a persistent group.
     *
     * @param $name
     * @return Group
     */
    public function create($name)
    {
        $class = $this->class;
        /** @var Group $group */
        $group = new $class($name, $this->dispatcher);
        $this->groups[$group->getName()] = $group;

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

    /**
     * @inheritdoc
     */
    public function onExpansionGroupAddUser(Group $group, $loginAdded)
    {
        // Nothing to
    }

    /**
     * @inheritdoc
     */
    public function onExpansionGroupRemoveUser(Group $group, $loginRemoved)
    {
        if (isset($this->groups[$loginRemoved])) {
            unset($this->groups[$loginRemoved]);
        }
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        // Nothing to do as the plugin don't now the rules to add or remove players to groups;
    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        foreach ($this->groups as $group) {
            $group->removeLogin($player->getLogin());
        }
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // Nothing to do as the plugin don't now the rules to add or remove players to groups;
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // Nothing to do as the plugin don't now the rules to add or remove players to groups;
    }
}
