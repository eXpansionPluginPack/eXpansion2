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
     * @param PlayerStorage $playerStorage
     * @param AdminGroups $adminGroups
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
     * @param Map $map
     * @param null $login
     * @param bool $force
     * @return bool
     * @throws \Exception
     */
    public function addMap(Map $map, $login = null, $force = false)
    {
        $player = null;
        if (!$login) {
            throw new \Exception("login is mandatory");
        }
        $player = $this->playerStorage->getPlayerInfo($login);
        $jbMap = new JukeboxMap($map, $player);

        if ($force) {
            $this->add($jbMap);

            return true;
        }
        // no some restrictions for admin
        if ($this->adminGroups->hasPermission($login, "jukebox")) {
            if ($this->checkMap($map) === false) {
                $this->add($jbMap);

                return true;
            }

        } else {
            // restrict 1 map per 1 login
            if ($this->checkLogin($login) === false && $this->checkMap($map) === false) {
                $this->add($jbMap);

                return true;
            }
        }

        return false;
    }

    /**
     * @param Map $map
     * @param $login
     * @param bool $force
     * @return bool
     * @throws \Exception
     */
    public function removeMap(Map $map, $login = null, $force = false)
    {
        if ($force) {
            return $this->remove($map);
        }

        if (!$login) {
            throw new \Exception("login is mandatory, when not forced.");
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
     */
    private function add(JukeboxMap $map)
    {
        array_push($this->mapQueue, $map);
    }

    /**
     * @param $login
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
     * @return bool
     */
    private function checkMap(Map $map)
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
