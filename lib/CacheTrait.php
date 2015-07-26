<?php
/**
 * Created on 09.01.14
 *
 * @author Sascha Schimke <sascha@schimke.me>
 */

namespace Sci\Cacheable;

use Doctrine\Common\Cache\Cache;
use Sci\Cacheable\CacheProxy;

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
        $this->cache    = $cache;
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
     *
     * @param int|null $lifetime
     *
     * @return CacheProxy
     */
    private function createCacheProxy($lifetime)
    {
        return new CacheProxy($this->cache, $this, is_null($lifetime) ? $this->lifetime : $lifetime);
    }
}
