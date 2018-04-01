<?php


namespace eXpansion\Framework\Core\Helpers;
use eXpansion\Bundle\Maps\Model\Map;
use oliverde8\AssociativeArraySimplified\AssociativeArray;


/**
 * Class CompatibleFetcher
 *
 * @package eXpansion\Framework\Core\Helpers;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class CompatibleFetcher
{
    /** For compatibility with every title/mode/script */
    const COMPATIBLE_ALL = "ALL";

    /**
     * Get a compatible data.
     *
     * @param $haystack
     * @param $title
     * @param $mode
     * @param $script
     *
     * @return mixed|null
     */
    public function getCompatibleData($haystack, $title, $mode, $script)
    {
        // List of choices order by importance.
        $choices = [
            [$title, $mode, $script],
            [$title, $mode, self::COMPATIBLE_ALL],
            [$title, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
            [self::COMPATIBLE_ALL, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
            // that are common to all titles.
            [self::COMPATIBLE_ALL, $mode, $script],
            [self::COMPATIBLE_ALL, $mode, self::COMPATIBLE_ALL],
            [self::COMPATIBLE_ALL, self::COMPATIBLE_ALL, self::COMPATIBLE_ALL],
        ];

        foreach ($choices as $choice) {
            $data = AssociativeArray::getFromKey($haystack, $choice);

            if (!is_null($data)) {
                return $data;
            }
        }

        return null;
    }
}