<?php

namespace eXpansion\Core\Plugins;

use eXpansion\Core\DataProviders\Listener\MatchDataListenerInterface;
use eXpansion\Core\Helpers\Time;
use eXpansion\Core\Services\Console;
use eXpansion\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\Structures\Map;

class Test implements MatchDataListenerInterface
{
    /** @var Connection */
    protected $connection;
    /** @var Console */
    protected $console;


    function __construct(Connection $connection, Console $console)
    {
        $this->connection = $connection;
        $this->console = $console;
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
        $this->console->writeln('$0f0Begin Map: $fff' . $map->name);
    }

    public function onEndMap(Map $map)
    {
        $this->console->writeln('$0f0End Map: $fff' . $map->name);
    }

    /**
     * Callback when player passes checkpoint.
     *
     * @param Player $player
     * @param $time
     * @param $lap
     * @param $index
     * @return mixed
     */
    public function onPlayerCheckpoint(Player $player, $time, $lap, $index)
    {
        $this->console->writeln('$0f0Checkpoint $ff0' . $index . ': $fff' . Time::TMtoMS($time, true) . ' $777' . $player->getNickName());

    }

    /**
     * Callback when player retire or finish
     * @param Player $player
     * @param $time 0 if retire, > 0 if finish
     * @return mixed
     */
    public function onPlayerFinish(Player $player, $time)
    {
        if ($time > 0) {
            $this->console->writeln('$777' . $player->getNickName() . ' $0f0Finished with time: $fff' . Time::TMtoMS($time, true));
        } else {
            $this->console->writeln('$777' . $player->getNickName() . ' $f00Retired');
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
}
