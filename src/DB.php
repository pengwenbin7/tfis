<?php

namespace Tfis;

/**
 * example:
 * $db = \Tfis\DB::instance($app->config("db"));
 * $db->query("select * from t00 where id < ? and id > ?", [3, 1]);
 */
class DB extends \PDO
{
    private static $_db;
    public function __construct($conf)
    {
        /**
         * if the database isn't mysql,
         * setting key named "dsn" in configure's "db" group
         */
        if (isset($conf["dsn"]) && $conf["dsn"]) {
            $dsn = $conf["dsn"];
        } else {
            $dsn = $conf["driver"] . ":host=" .
                 $conf["host"] . ";dbname=" .
                 $conf["dbname"];
        }
        self::$_db = parent::__construct($dsn, $conf["user"], $conf["password"],
                                         [\PDO::ATTR_PERSISTENT => true]);
        if ($conf["driver"] == "mysql") {
            parent::exec("set names utf8");
        }
    }
    
    public static function instance($conf = false)
    {
        if (!(self::$_db instanceof self)) {
            if (!$conf) {
                throw new \Exception("Database do not init yet.");
            }
            self::$_db = new self($conf);
        }
        return self::$_db;
    }

    public function query($sql, $option = [])
    {
        $sth = self::$_db->prepare($sql);
        if ($sth->execute($option)) {
            return $sth->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function exec($sql, $option = [])
    {
        $sth = self::$_db->prepare($sql);
        return $sth->execute($option);
    }
}