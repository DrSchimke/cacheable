<?php
/*
 * This file is part of the sci/cacheable package.
 *
 * (c) Sascha Schimke <sascha@schimke.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sci\Cacheable;

use Doctrine\Common\Cache\Cache;

class CacheProxy
{
    /** @var bool */
    private static $debug = false;

    /** @var Cache */
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
     */
    public function __construct(Cache $cache, Cacheable $object, $lifetime = 0)
    {
        $this->cache = $cache;
        $this->object = $object;
        $this->lifetime = $lifetime;
    }

    /**
     * @param bool $debug
     */
    public static function setDebug($debug)
    {
        self::$debug = $debug;
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

        if ($this->cache->contains($key)) {
            $result = $this->cache->fetch($key);
        } else {
            $result = call_user_func_array([$this->object, $name], $arguments);

            $this->cache->save($key, $result, $this->lifetime);
        }

        return $result;
    }

    /**
     * Creates cache key for result of method $name with $arguments.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return string
     */
    private function createKey($name, array $arguments)
    {
        $key = sprintf('%s::%s(%s)', get_class($this->object), $name, serialize($arguments));

        return self::$debug ? $key : sha1($key);
    }
}
