<?php
/**
 * Classes implementing this interface are able to cache their respective query methods.
 *
 * $object = new MyCacheableClass();
 * $object->setCache($cache, 60);          // inject $cache into $object with lifetime = 60 (done by service container)
 *
 * $object->getFooByBar($bar);             // original method call (non cached)
 * $object->cache()->getFooByBar($bar);    // cached method call (default lifetime = 60)
 * $object->cache(120)->getFooByBar($bar); // cached method call (lifetime = 120)
 *
 * @author Sascha Schimke <sascha@schimke.me>
 */

namespace Sci\Cacheable;

interface Cacheable
{
    /**
     * @param int|null $lifetime seconds
     *
     * @return $this
     */
    public function cache($lifetime = null);
}
