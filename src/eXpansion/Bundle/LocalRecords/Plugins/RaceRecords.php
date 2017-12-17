<?php

namespace eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Framework\GameTrackmania\DataProviders\Listener\RaceDataListenerInterface as TmRaceDataListenerInterface;

/**
 * Class RaceRecords
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RaceRecords extends BaseRecords implements TmRaceDataListenerInterface
{
    /**
     * @inheritdoc
     */
    public function startMap($map, $nbLaps)
    {
        if ($nbLaps == 1 && $map->lapRace) {
            $this->logger->info("Disabling race records.", ['nbLaps' => $nbLaps, 'map' => $map->lapRace]);
            $this->status = false;
            return;
        }

        parent::startMap($map, $nbLaps);
    }


    /**
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
    )
    {
        if (!$this->status) {
            return;
        }

        $eventData = $this->recordsHandler->addRecord($login, $raceTime, $curCps);
        if ($eventData) {
            $this->dispatchEvent($eventData);
        }
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
    )
    {
        // Nothing to do.
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
    )
    {
        // Nothing to do.
    }
}
