<?php

namespace Sci\Tests\Cacheable;

use Doctrine\Common\Cache\ArrayCache;
use Sci\Cacheable\Proxy;

class CacheableTest extends \PHPUnit_Framework_TestCase
{
    public function testWithoutCache()
    {
        // arrange
        $sut = new CacheableClass();

        // act
        $sut->cache()->fetchFoo(123);
        $sut->cache()->fetchFoo(123);
        $sut->cache()->fetchFoo(123);

        // assert
        $this->assertEquals(3, $sut->callcount);
    }

    public function testWithCache()
    {
        Proxy::setDebug(true);
        // arrange
        $cacheProvider = new ArrayCache();

        $sut = new CacheableClass();
        $sut->setCache($cacheProvider);

        // act
        $sut->cache()->fetchFoo(123);
        $sut->cache()->fetchFoo(123);
        $sut->cache()->fetchFoo(123);

        // assert
//        $this->assertEquals(1, $sut->callcount);
    }
}