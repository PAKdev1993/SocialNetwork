<?php

namespace app;

class Autoloader{

    static function register()
    {
        spl_autoload_register(array(__CLASS__,'autoload'));
    }

    static function autoload($class)
    {
        if(!strstr($class, 'Facebook'))
        {
            require_once($class . '.php');
        }
    }
}