<?php

namespace inc;

class Autoloader{

    static function register()
    {
        spl_autoload_register(array(__CLASS__,'autoload'));
    }

    static function autoload($class)
    {
        require_once('../../' . $class . '.php');
    }
}