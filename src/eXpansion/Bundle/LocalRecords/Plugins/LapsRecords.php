<?php

namespace eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Framework\GameTrackmania\DataProviders\Listener\RaceDataListenerInterface as TmRaceDataListenerInterface;
use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class RaceRecords
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class LapsRecords extends BaseRecords implements TmRaceDataListenerInterface
{
    /*
     * @inheritdoc
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        if (!$map->lapRace) {
            $this->status = false;
            return;
        }

        parent::onStartMapStart($count, $time, $restarted, $map);
    }


    /*
     * @inheritdoc
     */
    public function onPlayerEndRace(
        $login,
        $time,
        $raceTime,
        $stuntsScore,
        $cpInRace,
        $curCps,
        $blockId,
        $speed,
        $distance
    ) {
        // Nothing to do.
    }

    /*
     * @inheritdoc
     */
    public function onPlayerEndLap(
        $login,
        $time,
        $lapTime,
        $stuntsScore,
        $cpInLap,
        $curLapCps,
        $blockId,
        $speed,
        $distance
    ) {
        if (!$this->status) {
            return;
        }

        $eventData = $this->recordsHandler->addRecord($login, $lapTime, $curLapCps);
        if ($eventData) {
            $this->dispatchEvent($eventData);
        }
    }

    /*
     * @inheritdoc
     */
    public function onPlayerWayPoint(
        $login,
        $time,
        $raceTime,
        $lapTime,
        $stuntsScore,
        $cpInRace,
        $cpInLap,
        $curCps,
        $curLapCps,
        $blockId,
        $speed,
        $distance
    ) {
        // Nothing to do.
    }
}
