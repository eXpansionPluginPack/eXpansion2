<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class BaseDataListenerInterface
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface ListenerInterfaceMpScriptMap
{
    /**
     * Callback sent when the "StartMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return mixed
     */
    public function onStartMapStart($count, $time, $restarted, Map $map);

    /**
     * Callback sent when the "StartMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return mixed
     */
    public function onStartMapEnd($count, $time, $restarted, Map $map);

    /**
     * Callback sent when the "EndMap" section start.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return mixed
     */
    public function onEndMapStart($count, $time, $restarted, Map $map);

    /**
     * Callback sent when the "EndMap" section end.
     *
     * @param int     $count     Each time this section is played, this number is incremented by one
     * @param int     $time      Server time when the callback was sent
     * @param boolean $restarted true if the map was restarted, false otherwise
     * @param Map     $map       Map started with.
     *
     * @return mixed
     */
    public function onEndMapEnd($count, $time, $restarted, Map $map);
}
