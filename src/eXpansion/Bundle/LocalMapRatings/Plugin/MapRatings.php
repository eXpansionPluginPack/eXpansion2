<?php

namespace eXpansion\Bundle\LocalMapRatings\Plugin;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptMap;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpScriptPodium;
use Maniaplanet\DedicatedServer\Structures\Map;

class MapRatings implements ListenerInterfaceExpApplication, ListenerInterfaceMpScriptMap, ListenerInterfaceMpScriptPodium
{

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // TODO: Implement onApplicationInit() method.
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {

    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // TODO: Implement onApplicationStop() method.
    }

    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onStartMapStart($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onStartMapStart() method.
    }

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onStartMapEnd() method.
    }

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onEndMapStart($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onEndMapStart() method.
    }

    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return void
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map)
    {
        // TODO: Implement onEndMapEnd() method.
    }

    /**
     * Callback sent when the "onPodiumStart" section start.
     *
     * @param int $time Server time when the callback was sent
     * @return void
     */
    public function onPodiumStart($time)
    {
        // TODO: Implement onPodiumStart() method.
    }

    /**
     * Callback sent when the "onPodiumEnd" section end.
     *
     * @param int $time Server time when the callback was sent
     *
     * @return void
     */
    public function onPodiumEnd($time)
    {
        // TODO: Implement onPodiumEnd() method.
    }
}
