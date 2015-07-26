<?php

namespace Sci\Tests\Cacheable;

use Doctrine\Common\Cache\ArrayCache;
use Sci\Cacheable\Cacheable;
use Sci\Cacheable\Proxy;

class ProxyTest extends \PHPUnit_Framework_TestCase
{
    public function testCaching()
    {
        $cacheable = $this->getMock(Cacheable::class);
        $cacheable->expects($this->once())->method('getFoo');
        $sut = new Proxy(new ArrayCache(), $cacheable);

        $sut->getFoo();
    }

//    public function testCachingWithDebug()
//    {
//        Proxy::setDebug(true);
//
//        $this->doTest();
//    }
//
//    public function testCachingLifetime()
//    {
//        $defaultLifeTime = 100;
//        $lifeTime = 200;
//
//        $cache = new MyArrayCache();
//
//        $cacheable = new MyCacheableClass();
//        $cacheable->setCache($cache, $defaultLifeTime);
//
//        $cacheable->cache($lifeTime)->getTest("");
//
//        $this->assertEquals($lifeTime, MyArrayCache::$lastLifeTime);
//    }
//
//    public function testCachingDefaultLifetime()
//    {
//        $defaultLifeTime = 100;
//
//        $cache = new MyArrayCache();
//
//        $cacheable = new MyCacheableClass();
//        $cacheable->setCache($cache, $defaultLifeTime);
//
//        $cacheable->cache()->getTest("");
//
//        $this->assertEquals($defaultLifeTime, MyArrayCache::$lastLifeTime);
//    }

    private function doTest()
    {
        $expectedResult = [1, "string scalar", ["subarray"], new \stdClass(), STDIN];

        $cache = new ArrayCache();

        $cacheable = new MyCacheableClass();
        $cacheable->setCache($cache);

        $this->assertEquals(0, $cacheable->called, "Failed pre-condition that method has never been called");

        // preparation
        $result = $cacheable->cache()->getTest($expectedResult);
        $this->assertEquals(1, $cacheable->called, "Failed pre-condition that method ha been called once");
        $this->assertSame($expectedResult, $result);

        // the actual test
        $result = $cacheable->cache()->getTest($expectedResult);

        // assertions
        $this->assertEquals(1, $cacheable->called, "Failed asserting that cache has been used");
        $this->assertSame($expectedResult, $result);
    }
}


//class MyCacheableClass implements Cacheable
//{
//    use CacheTrait;
//
//    public $called = 0;
//
//    public function getTest($a)
//    {
//        ++$this->called;
//
//        return $a;
//    }
//
//    public function publicCacheSave($id, $data, $lifeTime = 0)
//    {
//        return $this->cacheSave($id, $data, $lifeTime);
//    }
//
//    public function publicCacheContains($id)
//    {
//        return $this->cacheContains($id);
//    }
//
//    public function publicCacheFetch($id)
//    {
//        return $this->cacheFetch($id);
//    }
//
//    public function publicCacheDelete($id)
//    {
//        return $this->cacheDelete($id);
//    }
//}
//
//class MyArrayCache extends ArrayCache
//{
//    public static $lastLifeTime;
//
//    protected function doSave($id, $data, $lifeTime = 0)
//    {
//        self::$lastLifeTime = $lifeTime;
//
//        return parent::doSave($id, $data, $lifeTime);
//    }
//}
