<?php

namespace AkniCallback\Helper;

/**
 * This is helper, that need for all functions.
 * Class Data
 * @package AkniCallback\Helper
 */
class Data
{
    /**
     * This method need to remove html tags and rounded whitespaces.
     * @param $value
     * @return string
     */
    public static function clearString($value)
    {
        return trim(strip_tags($value));
    }

    /**
     * This method unset value $val in array $arr.
     * @param $val
     * @param array $arr
     * @return array
     */
    public static function unsetArrayValue($val, array $arr)
    {
        $arr = array_flip($arr);
        unset ($arr[$val]);
        $arr = array_flip($arr);
        
        return $arr;
    }
}