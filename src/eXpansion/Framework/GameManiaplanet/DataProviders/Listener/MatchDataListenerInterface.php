<?php

namespace eXpansion\Framework\GameManiaplanet\DataProviders\Listener;

use Maniaplanet\DedicatedServer\Structures\Map;

/**
 * Class BaseDataListenerInterface
 *
 * @package eXpansion\Framework\GameManiaplanet\DataProviders\Listener;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
interface MatchDataListenerInterface
{
    /**
     * Callback sent when the "StartMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartMatchStart($count, $time);

    /**
     * Callback sent when the "StartMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartMatchEnd($count, $time);
}