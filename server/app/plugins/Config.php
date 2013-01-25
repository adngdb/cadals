<?php

class ConfigPlugin
{
    public static $config = array();

    static public function start($config)
    {
        self::$config = array_merge(self::$config, $config);
    }

    static public function onAtomikDispatchUri()
    {
        Atomik_Db::exec('SET names utf8');
    }
}
