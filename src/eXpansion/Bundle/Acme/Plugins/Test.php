<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\MatchDataListenerInterface;
use eXpansion\Framework\Core\DataProviders\Listener\TimerDataListenerInterface;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class Test implements MatchDataListenerInterface, TimerDataListenerInterface
{
    /** @var Connection */
    protected $connection;

    /** @var Console */
    protected $console;

    /** @var Time */
    protected $time;

    /** @var float|int */
    private $previousMemoryValue = 0;

    function __construct(Connection $connection, Console $console, Time $time)
    {
        $this->connection = $connection;
        $this->console = $console;
        $this->time = $time;
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
        $mem = memory_get_usage(true) / 1024;
        if ($this->previousMemoryValue != $mem) {

            $diff = ($mem - $this->previousMemoryValue);
            $msg = '$fff> Memory: $ff0'.$mem."kb ";

            if ($this->previousMemoryValue < $mem) {
                $msg .= ' $f00+'.$diff."kb";
            } else {
                $msg .= ' $0f0-'.$diff."kb";
            }
            $this->console->writeln($msg);

            $this->previousMemoryValue = $mem;
        }
    }
}
