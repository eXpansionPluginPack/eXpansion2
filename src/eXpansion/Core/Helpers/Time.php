<?php

namespace eXpansion\Core\Helpers;

class Time
{
    public static function MStoTM($string)
    {
        $timeLimit = explode(":", trim($string));
        if (count($timeLimit) == 1) {
            return intval($timeLimit[0] * 1000);
        } else {
            return intval($timeLimit[0] * 60 * 1000) + intval($timeLimit[1] * 1000);
        }
    }

    public static function TMtoMS($time, $milliseconds = false)
    {
        $time = intval($time);
        $ms = "";
        if ($milliseconds) {
            $ms = ":".str_pad(($time % 1000), 3, '0', STR_PAD_LEFT);
        }
        return gmdate("i:s", $time / 1000).$ms;
    }
}
