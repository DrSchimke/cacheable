<?php

/**
 * This file is part of the sci/cacheable package.
 *
 * (c) Sascha Schimke <sascha@schimke.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sci\Tests\Cacheable;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * Stub implementation of a in-memory CacheItemPool.
 *
 * Non-needed methods are not implemented.
 */
class ArrayPool implements CacheItemPoolInterface
{
    public $lastKey;
    public $lastLifetime;

    private $data = [];

    /**
     * @param string $key
     *
     * @return CacheItemInterface
     */
    public function getItem($key)
    {
        return isset($this->data[$key]) ? new CacheItem($key, $this->data[$key], true) : new CacheItem($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasItem($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param CacheItemInterface|CacheItem $item
     *
     * @return bool
     */
    public function save(CacheItemInterface $item)
    {
        $this->data[$item->getKey()] = $item->get();
        $this->lastKey = $item->getKey();
        $this->lastLifetime = $item->getTtl();

        return true;
    }

    public function getItems(array $keys = [])
    {
    }

    public function clear()
    {
    }

    public function deleteItem($key)
    {
    }

    public function deleteItems(array $keys)
    {
    }

    public function saveDeferred(CacheItemInterface $item)
    {
    }

    public function commit()
    {
    }
}
