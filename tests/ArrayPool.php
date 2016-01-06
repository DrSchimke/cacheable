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
 */
class ArrayPool implements CacheItemPoolInterface
{
    public $lastId;
    public $lastLifetime;

    private $data = [];

    /**
     * @param string $key
     *
     * @return CacheItemInterface
     */
    public function getItem($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : new CacheItem($key);
    }

    public function getItems(array $keys = [])
    {
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
        $this->data[$item->getKey()] = $item;
        $this->lastId = $item->getKey();
        $this->lastLifetime = $item->getTtl();

        return true;
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
