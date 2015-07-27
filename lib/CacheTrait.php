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

use Doctrine\Common\Cache\Cache;

trait CacheTrait
{
    /** @var Cache */
    private $cache;

    /** @var int */
    private $lifetime;

    /**
     * @param Cache $cache
     * @param int   $lifetime
     */
    public function setCache(Cache $cache, $lifetime = 0)
    {
        $this->cache = $cache;
        $this->lifetime = $lifetime;
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
        return new CacheProxy($this->cache, $this, is_null($lifetime) ? $this->lifetime : $lifetime);
    }
}
