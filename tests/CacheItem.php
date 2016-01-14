<?php

/**
 * This file is part of cacheable.
 *
 * (c) Sascha Schimke <sascha@schimke.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sci\Tests\Cacheable;

use Psr\Cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
    /** @var string */
    private $key;

    /** @var mixed */
    private $value;

    /** @var bool */
    private $hit;

    /** @var int|\DateInterval */
    private $ttl;

    /**
     * @param string $key
     * @param mixed  $value
     * @param bool   $hit
     */
    public function __construct($key, $value = null, $hit = false)
    {
        $this->key = $key;
        $this->value = $value;
        $this->hit = $hit;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isHit()
    {
        return $this->hit;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function set($value)
    {
        $this->value = $value;
        $this->hit = true;

        return $this;
    }

    /**
     * @param \DateTimeInterface $expiration
     *
     * @return static
     */
    public function expiresAt($expiration)
    {
        return $this;
    }

    /**
     * @param int|\DateInterval $time
     *
     * @return static
     */
    public function expiresAfter($time)
    {
        $this->ttl = $time;

        return $this;
    }

    public function getTtl()
    {
        return $this->ttl;
    }
}
