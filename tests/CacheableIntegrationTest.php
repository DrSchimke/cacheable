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

use Cache\Adapter\Chain\CachePoolChain;
use Cache\Adapter\Doctrine\DoctrineCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Cache\Adapter\Redis\RedisCachePool;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\RedisCache;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @group integrationTest
 */
class CacheableIntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function cacheItemPoolProvider()
    {
        return [
            [[$this, 'createRedisCachePool']],
            [[$this, 'createArrayCachePool']],
            [[$this, 'createDoctrineRedisCachePool']],
            [[$this, 'createDoctrineArrayCachePool']],
            [[$this, 'createChainCachePool']],
        ];
    }

    /**
     * @dataProvider cacheItemPoolProvider
     *
     * @test
     */
    public function it_should_use_cache(callable $cacheItemPoolFactory)
    {
        // arrange
        $pool = $cacheItemPoolFactory();

        $sut = new CacheableClass();
        $sut->setCache($pool, 2);

        // act
        $a = $sut->cache()->getDouble(123);
        $b = $sut->cache()->getDouble(123);
        $c = $sut->cache()->getDouble(123);

        // assert
        $this->assertEquals(246, $a);
        $this->assertEquals(246, $b);
        $this->assertEquals(246, $c);
        $this->assertEquals(1, $sut->callcount);
    }

    /**
     * @return CacheItemPoolInterface
     */
    private function createRedisCachePool()
    {
        $redis = new \Redis();
        $redis->connect('localhost');
        $redis->flushAll();

        return new RedisCachePool($redis);
    }

    /**
     * @return CacheItemPoolInterface
     */
    private function createArrayCachePool()
    {
        return new ArrayCachePool();
    }

    /**
     * @return CacheItemPoolInterface
     */
    private function createDoctrineRedisCachePool()
    {
        $redis = new \Redis();
        $redis->connect('localhost');
        $redis->flushAll();

        $cache = new RedisCache();
        $cache->setRedis($redis);

        return new DoctrineCachePool($cache);
    }

    /**
     * @return CacheItemPoolInterface
     */
    private function createDoctrineArrayCachePool()
    {
        return new DoctrineCachePool(new ArrayCache());
    }

    /**
     * @return CacheItemPoolInterface
     */
    private function createChainCachePool()
    {
        return new CachePoolChain([$this->createArrayCachePool(), $this->createRedisCachePool()]);
    }
}
