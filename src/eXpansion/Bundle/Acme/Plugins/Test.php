<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Bundle\Acme\Plugins\Gui\MemoryWidgetFactory;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class Test implements ListenerInterfaceMpLegacyMap, ListenerInterfaceExpTimer, StatusAwarePluginInterface
{

    /** @var  string */
    static public $memoryMsg;

    /** @var Connection */
    protected $connection;

    /** @var Console */
    protected $console;

    /** @var Time */
    protected $time;

    /** @var float|int */
    private $previousMemoryValue = 0;

    /** @var int */
    private $startMemValue = 0;

    /**
     * @var MemoryWidgetFactory
     */
    private $mlFactory;
    /**
     * @var PlayerStorage
     */
    private $players;

    /**
     * Test constructor.
     * @param Connection $connection
     * @param Console $console
     * @param Time $time
     * @param MemoryWidgetFactory $mlFactory
     * @param PlayerStorage $players
     */
    function __construct(
        PlayerStorage $players,
        Connection $connection,
        Console $console,
        Time $time,
        MemoryWidgetFactory $mlFactory
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->time = $time;
        $this->mlFactory = $mlFactory;
        $this->players = $players;
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
            if ($this->startMemValue < $diff) {
                $msg .= ' $f00('.round($diff / 1024)."kb)";
            } else {
                $msg .= ' $0f0('.round($diff / 1024)."kb)";
            }

            self::$memoryMsg = $msg;
            foreach ($this->players->getOnline() as $player) {
                $this->mlFactory->update($player->getLogin());
            }
            $this->console->writeln($msg);
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

    }

    /**
     * @param $currentMap
     * @param $previousMap
     */
    public function onExpansionMapChange($currentMap, $previousMap)
    {

    }

    /**
     * @param $nextMap
     * @param $previousNextMap
     */
    public function onExpansionNextMapChange($nextMap, $previousNextMap)
    {

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
            $this->startMemValue = memory_get_usage(true);
            foreach ($this->players->getOnline() as $player) {
                $this->mlFactory->create($player->getLogin());
            }

        }
    }

}
