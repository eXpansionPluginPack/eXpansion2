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

    public function onBeginMatch()
    {
        $this->console->writeln('$0f0Begin Match');
    }

    public function onEndMatch()
    {
        $this->console->writeln('$0f0End Match');
    }

    public function onBeginMap(Map $map)
    {
        $this->console->writeln('$0f0Begin Map: $fff'.$map->name);
    }

    public function onEndMap(Map $map)
    {
        $this->console->writeln('$0f0End Map: $fff'.$map->name);
    }

    /**
     * Callback when player passes checkpoint.
     *
     * @param Player $player
     * @param $time
     * @param $lap
     * @param $index
     * @return mixed|void
     */
    public function onPlayerCheckpoint(Player $player, $time, $lap, $index)
    {
        $this->console->writeln('$0f0Checkpoint $ff0'.$index.': $fff'.$this->time->milisecondsToTrackmania($time, true).' $777'.$player->getNickName());

    }

    /**
     * Callback when player retire or finish
     * @param Player $player
     * @param $time 0 if retire, > 0 if finish
     * @return mixed|void
     */
    public function onPlayerFinish(Player $player, $time)
    {
        if ($time > 0) {
            $this->console->writeln('$777'.$player->getNickName().' $0f0Finished with time: $fff'.$this->time->milisecondsToTrackmania($time, true));
        } else {
            $this->console->writeln('$777'.$player->getNickName().' $f00Retired');
        }
    }

    public function onBeginRound()
    {
        $this->console->writeln('$0f0 Begin Round');
    }

    public function onEndRound()
    {
        $this->console->writeln('$0f0 End Round');
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
