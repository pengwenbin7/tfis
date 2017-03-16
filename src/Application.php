<?php

namespace Tfis;

use Tfis\Request;
use Tfis\DB;
use Tfis\Dispatcher;

class Application
{
    private $_config;
    private static $_app = false;
    private function __construct($confPath)
    {
        $this->_config = parse_ini_file($confPath, true);
    }

    /**
     * init Application
     */
    public static function app($confPath = false)
    {
        if (!(self::$_app instanceof self)) {
            if ($confPath) {
                self::$_app = new self($confPath);
            } else {
                throw new \Exception("Application not init yet.");
            }
        }
        return self::$_app;
    }

    public static function instance()
    {
        if (!self::$_app) {
            throw new \Exception("Application not init yet.");
        }
        return self::$_app;
    }

    public function config($group = false, $key = false) {
        if ($group && !$key) {
            return $this->_config[$group];
        } else if ($group && $key) {
            return $this->_config[$group][$key];
        } else {
            return $this->_config;
        }
    }

    public function run()
    {
        $request = new Request($_SERVER);
        $dispatcher = Dispatcher::instance($request);
        $dispatcher->dispatch();
    }
}