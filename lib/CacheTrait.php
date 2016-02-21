<?php

/**
 * This file is part of the sci/cacheable package.
 *
 * (c) Sascha Schimke <sascha@schimke.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sci\Cacheable;

use Psr\Cache\CacheItemPoolInterface;

trait CacheTrait
{
    /** @var CacheItemPoolInterface */
    private $cache;

    /** @var int */
    private $lifetime;

    /** @var string */
    private $namespace;

    /**
     * @param CacheItemPoolInterface $cache
     * @param int $lifetime
     * @param string $namespace
     */
    public function setCache(CacheItemPoolInterface $cache, $lifetime = null, $namespace = null)
    {
        $this->cache = $cache;
        $this->lifetime = $lifetime;
        $this->namespace = $namespace;
    }

    /**
     * @param int|null $lifetime
     *
     * @return $this
     */
    public function cache($lifetime = null)
    {
        return is_null($this->cache) ? $this : $this->createCacheProxy($lifetime);
    }

    /**
     * @param int|null $lifetime
     *
     * @return CacheProxy
     */
    private function createCacheProxy($lifetime)
    {
        return new CacheProxy($this->cache, $this, is_null($lifetime) ? $this->lifetime : $lifetime, $this->namespace);
    }
}
