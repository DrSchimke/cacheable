<?php

namespace Sci\Tests\Cacheable;

use Doctrine\Common\Cache\ArrayCache as BaseArrayCache;

class ArrayCache extends BaseArrayCache
{
    public $lastId;
    public $lastData;
    public $lastLifetime;

    protected function doSave($id, $data, $lifetime = 0)
    {
        $this->lastId = $id;
        $this->lastData = $data;
        $this->lastLifetime = $lifetime;

        return parent::doSave($id, $data, $lifetime);
    }
}