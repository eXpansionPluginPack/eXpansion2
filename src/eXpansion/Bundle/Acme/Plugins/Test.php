<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMaplist;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class Test implements ListenerInterfaceMpLegacyMap, ListenerInterfaceExpTimer, StatusAwarePluginInterface
{

    static public $memoryMsg;
    /** @var Connection */
    protected $connection;

    /** @var Console */
    protected $console;

    /** @var Time */
    protected $time;

    /** @var float|int */
    private $previousMemoryValue = 0;
    private $startMemValue = 0;

    /**
     * @var ManialinkFactory
     */
    private $mlFactory;
    /**
     * @var Group
     */
    private $players;

    function __construct(
        Connection $connection,
        Console $console,
        Time $time,
        ManialinkFactory $mlFactory,
        Group $players
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->time = $time;
        $this->mlFactory = $mlFactory;
        $this->players = $players;
        $this->startMemValue = memory_get_usage(true);
    }

    public function onBeginMap(Map $map)
    {
        $this->console->writeln('$0f0Begin Map: $fff'.$map->name);
    }

    public function onEndMap(Map $map)
    {
        $this->console->writeln('$0f0End Map: $fff'.$map->name);
    }

    public function onPreLoop()
    {
        // do nothing
    }

    public function onPostLoop()
    {
        // do nothing
    }

    public function onEverySecond()
    {
        $mem = memory_get_usage(false);

        if ($this->previousMemoryValue != $mem) {
            $diff = ($mem - $this->previousMemoryValue);
            $msg = 'Memory: $0d0'.round($mem / 1024)."kb ";

            if ($this->previousMemoryValue < $mem) {
                $msg .= ' $f00+'.round($diff / 1024)."kb";
            } else {
                $msg .= ' $0f0'.round($diff / 1024)."kb";
            }

            $diff = ($mem - $this->startMemValue);
            if ($this->startMemValue  < $diff) {
                $msg .= ' $f00('.round($diff / 1024)."kb)";
            } else {
                $msg .= ' $0f0('.round($diff / 1024)."kb)";
            }

            self::$memoryMsg = $msg;
            $this->mlFactory->update($this->players);
            // $this->console->writeln($msg);
        }
        $this->previousMemoryValue = $mem;

    }

    /**
     * @param Map[] $oldMaps
     * @param string $currentMapUid
     * @param string $nextMapUid
     * @param bool $isListModified
     * @return mixed
     */
    public function onMapListModified($oldMaps, $currentMapUid, $nextMapUid, $isListModified)
    {
        // TODO: Implement onMapListModified() method.
    }

    public function onExpansionMapChange($currentMap, $previousMap)
    {
        // TODO: Implement onExpansionMapChange() method.
    }

    public function onExpansionNextMapChange($nextMap, $previousNextMap)
    {
        // TODO: Implement onExpansionNextMapChange() method.
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->mlFactory->create($this->players);
        }
    }

    /**
     * @return mixed
     */
    public function getMemoryMsg()
    {
        return $this->memoryMsg;
    }
}
