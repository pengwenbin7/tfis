<?php

namespace Tfis;

class Dispatcher
{
    private static $dispatcher;
    private $request;
    private $class;
    private $method;
    private $args;
    private function __construct($request)
    {
        $this->request = $request;
    }

    public static function instance($request)
    {
        if (self::$dispatcher instanceof self) {
            $this->request = $request;
            return self::$dispatcher;
        } else {
            self::$dispatcher = new self($request);            
            return self::$dispatcher;
        }
    }

    public function dispatch()
    {
        $arr = explode('/', $this->request->uri);
        if (!$arr[0]) {
            array_shift($arr);
        }
        $func = array_pop($arr);
        if (!$func) {
            $func = array_pop($arr);
        }
        $class = "Tfis\\controller\\" . implode("\\", $arr);
        try {
            $class = new \ReflectionClass($class);
            $method = $class->getMethod($func);
            $msg = $method->invoke(
                $class->newInstance($this->request));
            if ($msg) {
                echo json_encode([
                    "status" => 200,
                "msg" => $msg]);
            }
        } catch (\ReflectionException $e) {
            $result = ["status" => 404,
                       "msg" => $e->getMessage(),
            ];
            echo json_encode($result);
        }
    }
}