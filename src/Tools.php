<?php

namespace Attogram\SharedMedia\Api;

/**
 * SharedMedia Tools
 */
class Tools
{
    const VERSION = '0.9.5';

    /**
     * @param array $arrays
     */
    public static function flatten($arrays)
    {
        if (!is_array($arrays)) {
            return $arrays;
        }
        $flat = [];
        foreach ($arrays as $key => $val) {
            $flat[$key] = self::flattenArray($val);
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

    /**
     * @param string $str1
     * @param string $str2
     */
    public function isSelected($str1, $str2)
    {
        if ($str1 == $str2) {
            return ' selected ';
        }
    }

    /**
     * implode an array, using | as the glue
     *
     * @param array|mixed $values
     * @return string|mixed
     */
    public function valuesImplode($values)
    {
        if (!is_array($values)) {
            return $values;
        }
        return implode('|', $values);
    }

    /**
     * make a string safe for web output
     *
     * @param string|mixed $string
     */
    public function safeString($string)
    {
        if (!is_string($string)) {
            return $string;
        }
        return htmlentities($string);
    }
}
