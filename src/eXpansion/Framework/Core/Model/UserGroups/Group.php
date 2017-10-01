<?php

namespace eXpansion\Framework\Core\Model\UserGroups;

use eXpansion\Framework\Core\Services\Application\Dispatcher;
use eXpansion\Framework\Core\Services\Application\DispatcherInterface;

/**
 * A Group of users. Each group of users should have a plugin to handle user disconnects at the very least.
 *
 * @package eXpansion\Framework\Core\Model\UserGroups
 * @author Oliver de Cramer
 */
class Group
{
    const EVENT_NEW_USER = 'expansion.user_groups.user_add';
    const EVENT_REMOVE_USER = 'expansion.user_groups.user_remove';
    const EVENT_DESTROY = 'expansion.user_groups.group_destroy';

    /** @var DispatcherInterface */
    protected $dispatcher;

    /** @var string[] */
    protected $logins = [];

    /** @var bool Should the group destroy itself when empty. */
    protected $persistent = false;

    /** @var string The name of the group. */
    protected $name;

    /**
     * Group constructor.
     *
     * @param string $name
     * @param DispatcherInterface $dispatcher
     */
    public function __construct( $name = null, DispatcherInterface $dispatcher)
    {
        if (is_null($name)) {
            $this->name = spl_object_hash($this);
            $this->persistent = false;
        } else {
            $this->name = $name;
            $this->persistent = true;
        }

        $this->dispatcher = $dispatcher;
    }

    /**
     * Add user to the group.
     *
     * @param string $login
     */
    public function addLogin($login)
    {
        if (!isset($this->logins[$login])) {
            $this->logins[$login] = true;
            $this->dispatcher->dispatch(self::EVENT_NEW_USER, [$this, $login]);
        }
    }

    /**
     * Remove user from the group.
     *
     * @param $login
     */
    public function removeLogin($login)
    {
        if (isset($this->logins[$login])) {
            unset($this->logins[$login]);

            $this->dispatcher->dispatch(self::EVENT_REMOVE_USER, [$this, $login]);
        }

        if (!$this->isPersistent() && empty($this->logins)) {
            $this->dispatcher->dispatch(self::EVENT_DESTROY, [$this, $login]);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->persistent;
    }

    /**
     * Get list of all logins in the group.
     *
     * @return string[]
     */
    public function getLogins()
    {
        return array_keys($this->logins);
    }

    /**
     * Check if user is in the group.
     *
     * @param string $login
     *
     * @return bool
     */
    public function hasLogin($login)
    {
        return isset($this->logins[$login]);
    }
}
