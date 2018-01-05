<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Bundle\Acme\Plugins\Gui\MemoryWidgetFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class Test implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyMap, ListenerInterfaceExpTimer, StatusAwarePluginInterface
{

    /** @var  string */
    static public $memoryMsg;

    public $connectQueue = 0;

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
     * @var Group
     */
    private $playergroup;

    /**
     * Test constructor.
     * @param PlayerStorage       $players
     * @param Connection          $connection
     * @param Console             $console
     * @param Time                $time
     * @param Group               $playergroup
     * @param MemoryWidgetFactory $mlFactory
     */
    function __construct(
        PlayerStorage $players,
        Connection $connection,
        Console $console,
        Time $time,
        Group $playergroup,
        MemoryWidgetFactory $mlFactory
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->time = $time;
        $this->mlFactory = $mlFactory;
        $this->players = $players;
        $this->playergroup = $playergroup;
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
        if ($this->connectQueue > 0) {
            $this->connection->connectFakePlayer();
            $this->connectQueue--;
        }
    }

    public function onPostLoop()
    {

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
            if ($this->startMemValue > $mem) {
                $msg .= ' $0f0('.round($diff / 1024)."kb)";
            } else {
                $msg .= ' $f00('.round($diff / 1024)."kb)";
            }

            self::$memoryMsg = $msg;
            //    $this->mlFactory->update($this->playergroup);
            $this->console->writeln($msg);
        }

        $this->previousMemoryValue = $mem;

    }

    /**
     * @param Map[]  $oldMaps
     * @param string $currentMapUid
     * @param string $nextMapUid
     * @param bool   $isListModified
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

    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {

    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {

        $this->startMemValue = memory_get_usage(false);
        $this->mlFactory->create($this->playergroup);
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {

    }
}
