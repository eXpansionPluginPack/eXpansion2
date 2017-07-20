<?php

namespace eXpansion\Framework\Core\Helpers;

class TMString
{

    public static function trimStyles($string)
    {
        return preg_replace('/(\$[wnoitsgz><]|\$[lh]\[.+\]|\$[lh]|\$[0-9a-f]{3})+/i', '', $string);
    }

    public static function trimControls($string)
    {
        return preg_replace('/(\$[wnoitsgz><])+/i', '', $string);
    }

    public static function trimColors($string)
    {
        return preg_replace('/(\$[0-9a-f]{3})+/i', '', $string);
    }

    public static function trimLinks($string)
    {
        return preg_replace('/(\$[lh]\[.+\]|\$[lh])+/i', '', $string);
    }

}
