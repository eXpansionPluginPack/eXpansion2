<?php

namespace eXpansion\Framework\Core\Helpers;

class TMString
{


    public static function trimStyles($string)
    {
        return preg_replace('/(\$[o,w,s,z,m,h,l])/gi', '', $string);
    }

    public static function trimControls($string)
    {

    }

    public static function trimColors($string)
    {
        return preg_replace('/(\$[0-9,A-F]{3})/gi', "", $string);
    }

    public static function trimLinks($string)
    {

    }


}
