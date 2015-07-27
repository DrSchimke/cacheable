<?php

/*
 * This file is part of the sci/cacheable package.
 *
 * (c) Sascha Schimke <sascha@schimke.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sci\Tests\Cacheable;

use Sci\Cacheable\Cacheable;
use Sci\Cacheable\CacheTrait;

class CacheableClass implements Cacheable
{
    use CacheTrait;

    public $callcount = 0;

    public function getDouble($param)
    {
        ++$this->callcount;

        return 2 * $param;
    }
}
