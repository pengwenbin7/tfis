<?php

namespace Tfis\controller;

use Tfis\Application;
use Tfis\Cache;
use Tfis\DB;

/**
 * Controller 的基类
 * 如果新的 Controller 不继承此基类，
 * 则其构造方法应该和此类构造方法接受同样的参数
 */
class Base {
    protected $app;
    protected $cache;
    protected $db;
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
        $this->app = Application::instance();
        $cacheDir = $this->app->config("dir", "cache");
        $this->cache = Cache::instance($cacheDir);
        $dbConf = $this->app->config("db");
        $this->db = DB::instance($dbConf);
    }
}