<?php

namespace eXpansion\Framework\Core\Helpers;

class Time
{
    /**
     * Transform tm timestamp to mm:ss.ccc string
     *
     * @param int $time
     * @param bool $milliseconds
     *
     * @return string
     */
    public function timeToText($time, $milliseconds = false)
    {
        $time = intval($time);
        $ms = "";
        if ($milliseconds) {
            $ms = ".".str_pad((abs($time) % 1000), 3, '0', STR_PAD_LEFT);
        }
        if ($time > 0) {
            return gmdate("i:s", $time / 1000).$ms;
        } else {
            return "-".gmdate("i:s", abs($time / 1000)).$ms;
        }
    }

    /**
     * Transform mm:ss to tm timestamp
     *
     * @param string $string formatted like mm:ss
     *
     * @return int
     */
    public function textToTime($string)
    {
        $timeLimit = explode(":", trim($string));
        if (count($timeLimit) == 1) {
            return intval($timeLimit[0] * 1000);
        } else {
            return intval($timeLimit[0] * 60 * 1000) + intval($timeLimit[1] * 1000);
        }
    }
}
