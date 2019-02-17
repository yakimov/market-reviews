<?php

namespace S25\Utils;

class LocalStorage
{
    private static $store = [];

    public static function get($key)
    {
        if(isset(self::$store[$key]))
        {
            return self::$store[$key];
        }
        return false;
    }

    public static function put($key, $value)
    {
        self::$store[$key] = $value;
    }
}