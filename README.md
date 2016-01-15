# Cacheable – A transparent caching library

[![Build Status](https://secure.travis-ci.org/DrSchimke/cacheable.png)](http://travis-ci.org/DrSchimke/cacheable)

## Installation

Using [composer](https://getcomposer.org/download/):

```
composer require sci/cacheable dev-master
```

## Usage

Lets say, you have a class `Foo`, implementing a method `Foo::bar()` with quite high time/resource consumption:

```php
class Foo
{
    public function bar($a, $b)
    {
        // make some hard things with $a and $b
        ...
        
        return ...; // some result
    }
}

$foo = new Foo();

$bar = $foo->bar(1, 2); // takes some amount of time

// and later, again...
$bar = $foo->bar(1, 2); // takes the same amount of time, again
```

If there are no side-effects, the result of `Foo::bar()` is determined only by its arguments `$a` and `$b`.
So you could use some cache, if eventuelly the method is called again.
To avoid messing around with cache keys, you can use `sci\cacheable`:

```php

use Sci\Cacheable;
use Sci\CacheTrait;

class Foo implements Cacheable
{
    use CacheTrait;

    public function bar($a, $b)
    {
        // make some hard things with $a and $b
        ...
        
        return ...; // some result
    }
}

$foo = new Foo();
$foo->setCache(/* any PSR-6 cache pool interface */)

$bar = $foo->cache()->bar(1, 2); // 1st call takes some time, but now, the result is stored into cache

// and later, again...
$bar = $foo->cache()->bar(1, 2); // 2nd call's result comes directly from cache
```

### en détail

## Implementation

## License

All contents of this package are licensed under the [MIT license](LICENSE).
