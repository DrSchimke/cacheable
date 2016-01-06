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

use Sci\Cacheable\CacheProxy;

class CacheableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_work_without_cache()
    {
        // arrange
        $sut = new CacheableClass();

        // act
        $a = $sut->cache()->getDouble(123);
        $b = $sut->cache()->getDouble(123);
        $c = $sut->cache()->getDouble(123);

        // assert
        $this->assertEquals(246, $a);
        $this->assertEquals(246, $b);
        $this->assertEquals(246, $c);
        $this->assertEquals(3, $sut->callcount);
        $this->assertSame($sut, $sut->cache());
    }

    /**
     * @test
     */
    public function it_should_use_cache()
    {
        // arrange
        $cache = new ArrayPool();

        $sut = new CacheableClass();
        $sut->setCache($cache);

        // act
        $a = $sut->cache()->getDouble(123);
        $b = $sut->cache()->getDouble(123);
        $c = $sut->cache()->getDouble(123);

        // assert
        $this->assertEquals(246, $a);
        $this->assertEquals(246, $b);
        $this->assertEquals(246, $c);
        $this->assertEquals(1, $sut->callcount);
        $this->assertInstanceOf(CacheProxy::class, $sut->cache());
    }

    /**
     * @test
     */
    public function it_should_use_cache_lifetime()
    {
        // arrange
        $cache = new ArrayPool();
        $lifetime = 300;

        $sut = new CacheableClass();
        $sut->setCache($cache, $lifetime);

        // act
        $sut->cache()->getDouble(123);

        // assert
        $this->assertEquals($lifetime, $cache->lastLifetime);
    }

    /**
     * @test
     */
    public function it_should_use_local_cache_lifetime_if_given()
    {
        // arrange
        $cache = new ArrayPool();

        $lifetime = 300;
        $shorterLifetime = 60;

        $sut = new CacheableClass();
        $sut->setCache($cache, $lifetime);

        // act
        $sut->cache($shorterLifetime)->getDouble(123);

        // assert
        $this->assertEquals($shorterLifetime, $cache->lastLifetime);
    }

    /**
     * @test
     */
    public function it_should_use_correct_cache_keys()
    {
        // arrange
        $cache = new ArrayPool();

        $sut = new CacheableClass();
        $sut->setCache($cache);

        // act
        $a1 = $sut->cache()->getDouble(123);
        $key1 = $cache->lastId;
        $a2 = $sut->cache()->getDouble(123);
        $key2 = $cache->lastId;

        $b1 = $sut->cache()->getDouble(234);
        $key3 = $cache->lastId;
        $b2 = $sut->cache()->getDouble(234);
        $key4 = $cache->lastId;

        // assert
        $this->assertEquals(246, $a1);
        $this->assertEquals(246, $a2);
        $this->assertEquals(468, $b1);
        $this->assertEquals(468, $b2);

        $this->assertEquals($key1, $key2);
        $this->assertEquals($key3, $key4);
        $this->assertNotEquals($key1, $key3);
    }
}
