<?php

namespace eXpansion\Core\Model\UserGroups;

/**
 * A Group of users. Each group of users should have a plugin to handle unser disconnects at the very least.
 *
 * @package eXpansion\Core\Model\UserGroups
 * @author Oliver de Cramer
 */
class Group
{
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
     */
    public function __construct($name = null)
    {
        if (is_null($name)) {
            $this->name = spl_object_hash($this);
            $this->persistent = false;
        } else {
            $this->name = $name;
            $this->persistent = true;
        }
    }

    /**
     * Add user to the group.
     *
     * @param string $login
     */
    public function addLogin($login)
    {
        $this->logins[$login] = true;
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