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

/**
 * Classes implementing this interface are able to cache their respective query methods.
 *
 * $object = new MyCacheableClass();
 * $object->setCache($cache, 60);          // inject $cache into $object with lifetime = 60 (done by service container)
 *
 * $object->getFooByBar($bar);             // original method call (non cached)
 * $object->cache()->getFooByBar($bar);    // cached method call (default lifetime = 60)
 * $object->cache(120)->getFooByBar($bar); // cached method call (lifetime = 120)
 */
interface Cacheable
{
    /**
     * @param int|null $lifetime seconds
     *
     * @return $this
     */
    public function cache($lifetime = null);
}
