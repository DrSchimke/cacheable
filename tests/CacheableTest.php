<?php

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
        $cacheProvider = new ArrayCache();

        $sut = new CacheableClass();
        $sut->setCache($cacheProvider);

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
        $cacheProvider = new ArrayCache();
        $lifetime = 300;

        $sut = new CacheableClass();
        $sut->setCache($cacheProvider, $lifetime);

        // act
        $sut->cache()->getDouble(123);

        // assert
        $this->assertEquals($lifetime, $cacheProvider->lastLifetime);
    }

    /**
     * @test
     */
    public function it_should_use_local_cache_lifetime_if_given()
    {
        // arrange
        $cacheProvider = new ArrayCache();

        $lifetime = 300;
        $shorterLifetime = 60;

        $sut = new CacheableClass();
        $sut->setCache($cacheProvider, $lifetime);

        // act
        $sut->cache($shorterLifetime)->getDouble(123);

        // assert
        $this->assertEquals($shorterLifetime, $cacheProvider->lastLifetime);
    }

    /**
     * @test
     */
    public function it_should_use_correct_cache_keys()
    {
        // arrange
        $cacheProvider = new ArrayCache();

        $sut = new CacheableClass();
        $sut->setCache($cacheProvider);

        // act
        $a1 = $sut->cache()->getDouble(123);
        $key1 = $cacheProvider->lastId;
        $a2 = $sut->cache()->getDouble(123);
        $key2 = $cacheProvider->lastId;

        $b1 = $sut->cache()->getDouble(234);
        $key3 = $cacheProvider->lastId;
        $b2 = $sut->cache()->getDouble(234);
        $key4 = $cacheProvider->lastId;

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
