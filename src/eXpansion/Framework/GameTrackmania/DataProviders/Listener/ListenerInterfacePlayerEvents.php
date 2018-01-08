<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders\Listener;

/**
 * Class RaceDataListenerInterface
 *
 * @package eXpansion\Framework\GameTrackmania\DataProviders\Listener;
 * @author reaby
 */
interface ListenerInterfacePlayerEvents
{

    /**
     * @param string $login
     * @return void
     */
    public function onPlayerStartLine($login);

    /**
     * @param string $login
     * @param int $nbRespawn
     * @return void
     */
    public function onPlayerRespawn($login, $nbRespawn);

    /**
     * @param string $login
     * @return void
     */
    public function onPlayerGiveUp($login);

    /**
     * @param string $login
     * @param float $score
     * @param string $figure
     * @param float $angle
     * @param int $points
     * @param int $compo
     * @param bool $isStraight
     * @param bool $isReverse
     * @param bool $isMasterjump
     * @param float $factor
     * @return void
     *
     */
    public function onPlayerStunt(
        $login,
        $score,
        $figure,
        $angle,
        $points,
        $compo,
        $isStraight,
        $isReverse,
        $isMasterjump,
        $factor
    );


}
