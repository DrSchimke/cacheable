<?php

namespace Sci\Tests\Cacheable;

use Sci\Cacheable\Cacheable;
use Sci\Cacheable\CacheTrait;

class CacheableClass implements Cacheable
{
    use CacheTrait;

    public $callcount = 0;

    public function fetchFoo($param)
    {
        ++$this->callcount;
        
        return $param;
    }
}