<?php

namespace Core;

class Config
{
    private static $configs = [];


    public static function Init()
    {
        foreach (glob("config/*.php") as $fileName) {
            $configName = explode("/", $fileName)[1];
            $configName = str_replace(".php", "", $configName);
            self::$configs[$configName] = require_once $fileName;
        }

    }

    public static function get($key, $default = null)
    {
        $config = explode(".", $key);
        $configContainer = $config[0];
        $configKey = $config[1];
        $configs = self::$configs[$configContainer];
        if ($config) {
            $configValue = $configs[$configKey];
            if ($configValue) {
                return $configValue;
            }
        }
        return $default;
    }


}