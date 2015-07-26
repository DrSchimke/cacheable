<?php

namespace Sci\Cacheable;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\CacheProvider;

class Proxy
{
    private static $debug = false;

    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * @var mixed
     */
    private $object;

    /**
     * @var int
     */
    private $lifetime;

    /**
     * @param CacheProvider $cache
     * @param mixed         $object
     * @param int           $lifetime
     *
     * @throws \LogicException
     */
    public function __construct(Cache $cache, Cacheable $object, $lifetime = 0)
    {
        $this->cache    = $cache;
        $this->object   = $object;
        $this->lifetime = $lifetime;
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return bool|mixed|string
     */
    public function __call($name, array $arguments)
    {
        $key = $this->createKey($name, $arguments);
        var_dump($key);

        if ($this->cache->contains($key)) {
            $result = $this->cache->fetch($key);
        } else {
            $result = call_user_func_array([$this->object, $name], $arguments);

            $this->cache->save($key, $result, $this->lifetime);
        }

        return $result;
    }

    public static function setDebug($debug)
    {
        self::$debug = $debug;
    }

    /**
     * Creates cache key for result of method $name with $arguments
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return string
     */
    private function createKey($name, array $arguments)
    {
        $key = sprintf('%s::%s(%s)', get_class($this->object), $name, serialize($arguments));

        return self::$debug ? $key : md5($key);
    }

    /**
     * @param array $arguments
     *
     * @return array
     */
    private function arrayToString(array $arguments)
    {
        $result = [];

        foreach ($arguments as $key => $argument) {
            if (is_scalar($argument)) {
                $tmp = $argument;
            } elseif (is_array($argument)) {
                $tmp = $this->arrayToString($argument);
            } elseif (is_object($argument)) {
                $tmp = $this->objectToString($argument);
            } else {
                $tmp = $this->miscToString($argument);
            }

            $result[] = sprintf("%s: %s", $key, $tmp);
        }

        return sprintf("(%s)", implode(", ", $result));
    }

    /**
     * @param $argument
     *
     * @return string
     */
    private function objectToString($argument)
    {
        $class = get_class($argument);
        $class = explode("\\", $class);
        $class = $class[count($class) - 1];

        return sprintf("%s_%s", $class, $this->miscToString($argument));
    }

    /**
     * @param $argument
     *
     * @return string
     */
    private function miscToString($argument)
    {
        return substr(md5(print_r($argument, true)), 0, 8);
    }
}
