<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class RaceDataListenerInterface
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface ListenerInterfaceMpScriptPodium
{
    /**
     * Callback sent when the "onPodiumStart" section start.
     *
     * @param int     $time      Server time when the callback was sent
     * @return void
     */
    public function onPodiumStart($time);

    /**
     * Callback sent when the "onPodiumEnd" section end.
     *
     * @param int     $time      Server time when the callback was sent
     *
     * @return void
     */
    public function onPodiumEnd($time );


}
