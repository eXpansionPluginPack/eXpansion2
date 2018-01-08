<?php

namespace eXpansion\Bundle\LocalRecords\Plugins;

use eXpansion\Framework\GameTrackmania\DataProviders\Listener\ListenerInterfaceRaceData;

/**
 * Class RaceRecords
 *
 * @package eXpansion\Bundle\LocalRecords\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class RaceRecords extends BaseRecords implements ListenerInterfaceRaceData
{
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
        $eventData = $this->recordsHandler->addRecord($login, $raceTime, $curCps);
        if ($eventData) {
            $this->dispatchEvent($eventData);
        }
    }
}
