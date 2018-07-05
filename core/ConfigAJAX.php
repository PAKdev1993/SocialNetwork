<?php

namespace core;

class ConfigAJAX
{
    static $ROOT;
    
    static function getROOT()
    {
        self::$ROOT = 'C:/wamp64/www/WEindev/'; //#todo changer cette valeure quand on sera sur le serveur online
        return self::$ROOT;
    }
}