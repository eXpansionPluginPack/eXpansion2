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

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndMatchStart($count, $time);

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndMatchEnd($count, $time);

    /**
     * Callback sent when the "StartTurn" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartTurnStart($count, $time);

    /**
     * Callback sent when the "StartTurn" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartTurnEnd($count, $time);

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndTurnStart($count, $time);

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndTurnEnd($count, $time);

     /**
     * Callback sent when the "StartRound" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartRoundStart($count, $time);

    /**
     * Callback sent when the "StartRound" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onStartRoundEnd($count, $time);

    /**
     * Callback sent when the "EndMatch" section start.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndRoundStart($count, $time);

    /**
     * Callback sent when the "EndMatch" section end.
     *
     * @param int $count Each time this section is played, this number is incremented by one
     * @param int $time  Server time when the callback was sent
     *
     * @return mixed
     */
    public function onEndRoundEnd($count, $time);

}
