<?php

namespace Sci\Tests\Cacheable;

use Doctrine\Common\Cache\CacheProvider;

/**
 * @group cache
 * @group unitTest
 */
class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * @var MyCacheableClass
     */
    private $cacheableClass;

    public function setUp()
    {
        $this->cache = $this->getMock('Doctrine\Common\Cache\CacheProvider', [
                "doContains", "doFetch", "doSave", "doDelete", "doFlush", "doGetStats",
                "contains", "fetch", "save", "delete"
        ]);

        $this->cacheableClass = new MyCacheableClass();
        $this->cacheableClass->setCache($this->cache);
    }

    public function testCacheContains()
    {
        return;
        $expectedResult = true;

        $this->cache
            ->expects($this->once())
            ->method("contains")
            ->with($this->equalTo(1))
            ->will($this->returnValue($expectedResult));

        $result = $this->cacheableClass->publicCacheContains(1);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCacheFetch()
    {
        $expectedResult = "result";

        $this->cache
            ->expects($this->once())
            ->method("fetch")
            ->with($this->equalTo(1))
            ->will($this->returnValue($expectedResult));

        $result = $this->cacheableClass->publicCacheFetch(1);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCacheDelete()
    {
        $expectedResult = false;

        $this->cache
            ->expects($this->once())
            ->method("delete")
            ->with($this->equalTo(1))
            ->will($this->returnValue($expectedResult));

        $result = $this->cacheableClass->publicCacheDelete(1);

        $this->assertEquals($expectedResult, $result);
    }

    public function testCacheSave()
    {
        $expectedResult = true;

        $this->cache
            ->expects($this->once())
            ->method("save")
            ->with($this->equalTo(1), $this->equalTo("test"), $this->equalTo(3600))
            ->will($this->returnValue($expectedResult));

        $result = $this->cacheableClass->publicCacheSave(1, "test", 3600);

        $this->assertEquals($expectedResult, $result);
    }
}
