<?php

namespace Attogram\SharedMedia\Api;

/**
 * SharedMedia Tools
 */
class Tools
{
    const VERSION = '0.9.1';

    public static function flatten($arrays)
    {
        if (!is_array($arrays)) {
            return [];
        }
        $flat = [];
        foreach ($arrays as $array) {
            $flat[] = self::flattenArray($array);
        }
        return $flat;
    }

    /**
     * flatten a multi-dimensional array, with concatenated keys
     *
     * @param array $array
     * @param string $prefix Optional
     * @return array
     */
    public static function flattenArray($array, $prefix = '')
    {
        if (!is_array($array)) {
            return $array;
        }
        $result = [];
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $result += self::flattenArray($val, $prefix.$key.'.');
                continue;
            }
            $result[$prefix.$key] = $val;
        }
        return $result;
    }

    /**
     * @param string $strin
     * @return boolean
     */
    public static function isGoodString($string)
    {
        if (is_string($string) && $string) {
            return true;
        }
        return false;
    }
}
