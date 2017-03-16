<?php

namespace Tfis;

class Cache
{
    protected $directory;
    private static $cache;
    /**
     * Create a new file cache store instance.
     *
     * @param  string  $directory
     * @return void
     */
    private function __construct($directory)
    {
        $this->directory = $directory;
    }

    public static function instance($directory)
    {
        if (!(self::$cache instanceof self)) {
            self::$cache = new self($directory);
        }
        return self::$cache;
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string $key
     * @return mixed
     */
    public function get($key)
    {
        $path = $this->path($key);
        if (!file_exists($path)) {
            return null;
        }
        $contents = unserialize(file_get_contents($path));
        $expire = $contents["expire"];
        if ($expire == 0 || $expire > time()) {
            return $contents["data"];
        } else {
            $this->forget($key);
            return null;
        }
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  float|int  $minutes
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        if ($minutes != 0) {
            $arr["expire"] = time() + $minutes * 60;
        } else {
            $arr["expire"] = 0;
        }
        $arr["data"] = $value;
        $path = $this->path($key);
        if (!file_exists($dir = dirname($path))) {
            mkdir($dir, 0777, true);
        }
        $fp = fopen($this->path($key), "w");
        fwrite($fp, serialize($arr));
        fclose($fp);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key)
    {
        $file = $this->path($key);
        if (file_exists($file)) {
            return unlink($file);
        }
        return false;
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function forever($key, $value)
    {
        $this->put($key, $value, 0);
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush()
    {
        return $this->deleteDirectory($this->directory, true);
    }

    /**
     * Recursively delete a directory.
     *
     * The directory itself may be optionally preserved.
     *
     * @param  string  $directory
     * @param  bool    $preserve
     * @return bool    $sucess
     */
    protected function deleteDirectory($dir, $preserve = false)
    {
        $sucess = true;
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item == "." || $item == "..") {
                continue;
            }
            if (is_dir("$dir/$item")) {
                $this->deleteDirectory("$dir/$item", false);
            } else {
                $sucess = unlink("$dir/$item") && $sucess;
            }
        }
        if (!$preserve) {
            $sucess = rmdir($dir) && $sucess;
        }
        return $sucess;
    }
    
    /**
     * Get the full path for the given cache key.
     *
     * @param  string  $key
     * @return string
     */
    protected function path($key)
    {
        $parts = array_slice(str_split($hash = sha1($key), 2), 0, 2);
        return $this->directory.'/'.implode('/', $parts).'/'.$hash;
    }

}