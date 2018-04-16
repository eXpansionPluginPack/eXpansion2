<?php

namespace eXpansion\Bundle\Maps\Services;

use eXpansion\Bundle\Maps\Structure\JukeboxMap;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Structures\Map;

class JukeboxService
{
    /** @var JukeboxMap[] */
    protected $mapQueue = [];

    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var AdminGroups
     */
    private $adminGroups;

    /**
     * JukeboxService constructor.
     *
     * @param PlayerStorage $playerStorage
     * @param AdminGroups   $adminGroups
     */
    public function __construct(PlayerStorage $playerStorage, AdminGroups $adminGroups)
    {
        $this->playerStorage = $playerStorage;
        $this->adminGroups = $adminGroups;
    }

    /**
     * @return JukeboxMap[]
     */
    public function getMapQueue()
    {
        return $this->mapQueue;
    }

    /**
     * @return JukeboxMap|null
     */
    public function getFirst()
    {
        reset($this->mapQueue);

        return current($this->mapQueue);
    }

    /**
     * Adds map as first item
     *
     * @param Map  $map
     * @param null $login
     * @param bool $force
     *
     * @return bool
     */
    public function addMapFirst(Map $map, $login = null, $force = false)
    {
        return $this->addMap($map, $login, $force, true);
    }

    /**
     * Adds map as last item
     *
     * @param Map  $map
     * @param null $login
     * @param bool $force
     *
     * @return bool
     */
    public function addMapLast(Map $map, $login = null, $force = false)
    {
        return $this->addMap($map, $login, $force, false);
    }

    /**
     * Adds map as last or first item
     *
     * @param Map  $map
     * @param null $login
     * @param bool $force
     * @param bool $addFirst
     *
     * @return bool
     */
    public function addMap(Map $map, $login = null, $force = false, $addFirst = false)
    {

        if (!$login) {
            return false;
        }

        $player = $this->playerStorage->getPlayerInfo($login);
        $jbMap = new JukeboxMap($map, $player);

        if ($force) {
            $this->add($jbMap, $addFirst);

            return true;
        }
        // no some restrictions for admin
        if ($this->adminGroups->hasPermission($login, "jukebox")) {
            if ($this->checkMap($map) === false) {
                $this->add($jbMap, $addFirst);

                return true;
            }

        } else {
            // restrict 1 map per 1 login
            if ($this->checkLogin($login) === false && $this->checkMap($map) === false) {
                $this->add($jbMap, $addFirst);

                return true;
            }
        }

        return false;
    }

    /**
     * @param Map  $map
     * @param      $login
     * @param bool $force
     *
     * @return false;
     */
    public function removeMap(Map $map, $login = null, $force = false)
    {
        if ($force) {
            return $this->remove($map);
        }

        if (!$login) {

            return false;
        }
        // no some restrictions for admin
        if ($this->adminGroups->hasPermission($login, "jukebox")) {
            return $this->remove($map);
        } else {
            // restrict 1 map per 1 login
            $check = $this->getMap($login);
            if ($check && $check->getMap() === $map) {
                return $this->remove($map);
            }
        }

        return false;
    }

    /**
     * @param JukeboxMap $map
     * @param bool       $addFirst
     *
     * @return void
     */
    private function add(JukeboxMap $map, $addFirst = false)
    {
        if ($addFirst) {
            array_unshift($this->mapQueue, $map);
        } else {
            array_push($this->mapQueue, $map);
        }

    }

    /**
     * @param $login
     *
     * @return bool|JukeboxMap
     */
    public function getMap($login)
    {
        foreach ($this->mapQueue as $jukeboxMap) {
            if ($jukeboxMap->getPlayer()->getLogin() == $login) {
                return $jukeboxMap;
            }
        }

        return false;
    }

    /**
     * @param Map $map
     *
     * @return bool
     */
    private function remove(Map $map)
    {
        foreach ($this->mapQueue as $idx => $jukeboxMap) {
            if ($jukeboxMap->getMap() === $map) {
                unset($this->mapQueue[$idx]);

                return true;
            }
        }

        return false;
    }


    /**
     * checks if login exists on queue
     * @param string $login
     * @return bool
     */
    private function checkLogin($login)
    {
        foreach ($this->mapQueue as $jukeboxMap) {
            if ($jukeboxMap->getPlayer()->getLogin() == $login) {
                return true;
            }
        }

        return false;
    }

    /**
     * checks if map exists on queue
     * @param Map $map
     *
     * @return bool
     */
    public function checkMap(Map $map)
    {
        foreach ($this->mapQueue as $jukeboxMap) {
            if ($jukeboxMap->getMap() === $map) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     */
    public function clearMapQueue()
    {
        $this->mapQueue = [];
    }

}
