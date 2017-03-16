<?php

namespace Tfis;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;

class Log
{
    private static $log;
    private $logRoot;
    private function __construct() {}
    private function __clone() {}

    public static function error($error)
    {
        self::init();
    }
    
}