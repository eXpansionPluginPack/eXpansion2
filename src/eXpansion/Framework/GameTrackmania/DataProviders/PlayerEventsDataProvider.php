<?php

namespace eXpansion\Framework\GameTrackmania\DataProviders;

use eXpansion\Framework\Core\DataProviders\AbstractDataProvider;


/**
 * Class RaceDataProvider
 *
 * @package eXpansion\Framework\GameTrackmania\DataProviders;
 * @author reaby
 */
class PlayerEventsDataProvider extends AbstractDataProvider
{

    /**
     * @param $params
     *  [
     * "{
     * "time": 123456 //< Server time when the event occured,
     * "login": "PlayerLogin",
     * "nbrespawns": 5, //< Number of respawns since the beginning of the race
     * "racetime": 123456, //< Total race time in milliseconds
     * "laptime": 45678, //< Lap time in milliseconds
     * "stuntsscore": 3457, //< Stunts score
     * "checkpointinrace": 13, //< Number of checkpoints crossed since the beginning of the race
     * "checkpointinlap": 4, //< Number of checkpoints crossed since the beginning of the lap
     * "speed": 456.45, //< Speed of the player in km/h
     * "distance": 398.49 //< Distance traveled by the player since the beginning of the race
     * }"
     * ]
     */
    public function onRespawn($params)
    {
        $this->dispatch(
            'onPlayerRespawn',
            [
                $params['login'],
                $params['nbrespawns'],
            ]
        );
    }

    /**
     *
     */
    public function onGiveUp($params)
    {
        $this->dispatch(
            'onPlayerRespawn',
            [
                $params['login'],
            ]);
    }

    public function onStartLine($params)
    {
        $this->dispatch(
            'onPlayerStartLine',
            [
                $params['login'],
            ]);
    }

    /**
     * @param $params
     * "time": 123456 //< Server time when the event occured,
     * "login": "PlayerLogin",
     * "racetime": 123456, //< Total race time in milliseconds
     * "laptime": 45678, //< Lap time in milliseconds
     * "stuntsscore": 3457, //< Stunts score
     * "figure": "EStuntFigure::Roll", //< Name of the figure
     * "angle": 125, //< Angle of the car
     * "points": 18, //< Point awarded by the figure
     * "combo": 35, //< Combo counter
     * "isstraight": true, //< Is the car straight
     * "isreverse": false, //< Is the car reversed
     * "ismasterjump": false,
     * "factor": 0.5 /
     */
    public function onPlayerStunt($params)
    {
        $this->dispatch(
            'onPlayerStunt',
            [
                $params['login'],
                $params['stuntsscore'],
                $params['figure'],
                $params['angle'],
                $params['points'],
                $params['combo'],
                $params['isstraight'],
                $params['isreverse'],
                $params['ismasterjump'],
                $params['factor'],
            ]);
    }


}
