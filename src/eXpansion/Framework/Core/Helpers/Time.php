<?php

namespace eXpansion\Framework\Core\Helpers;

class Time
{
    /**
     * Transform tm timestamp to mm:ss.ccc string
     *
     * @param int  $time
     * @param bool $milliseconds
     * @param bool $pad
     * @return string
     */
    public function timeToText($time, $milliseconds = false, $pad = true)
    {
        $sign = "";
        if ($time < 0) {
            $sign = "-";
        }
        $time = abs($time);

        if ($pad) {
            $cent = str_pad(($time % 1000), 3, '0', STR_PAD_LEFT);
            $time = floor($time / 1000);
            $sec = str_pad($time % 60, 2, '0', STR_PAD_LEFT);
            $min = str_pad(floor($time / 60), 2, '0', STR_PAD_LEFT);
            $hour = str_pad(floor($time / 60 / 60), 1, '0');
        } else {
            $cent = str_pad(($time % 1000), 3, '0', STR_PAD_LEFT);
            $time = floor($time / 1000);
            $sec = $time % 60;
            $min = floor($time / 60);
            $hour = floor($time / 60 / 60);
        }

        $textTime = $min.':'.$sec;
        if (floor($time / 60 / 60) > 0) {
            $textTime = $hour."'".$textTime;
        }

        if ($milliseconds) {
            $textTime = $textTime.'.'.$cent;
        }

        return $sign.$textTime;
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
