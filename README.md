# Call all callables from the observable from within a RecoilPHP coroutine

[![Build Status](https://travis-ci.com/WyriHaximus/recoilphp-queue-caller.svg?branch=master)](https://travis-ci.com/WyriHaximus/recoilphp-queue-caller)
[![Latest Stable Version](https://poser.pugx.org/WyriHaximus/recoil-queue-caller/v/stable.png)](https://packagist.org/packages/WyriHaximus/recoil-queue-caller)
[![Total Downloads](https://poser.pugx.org/WyriHaximus/recoil-queue-caller/downloads.png)](https://packagist.org/packages/WyriHaximus/recoil-queue-caller)
[![Code Coverage](https://scrutinizer-ci.com/g/WyriHaximus/recoilphp-queue-caller/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/WyriHaximus/recoilphp-queue-caller/?branch=master)
[![License](https://poser.pugx.org/WyriHaximus/recoil-queue-caller/license.png)](https://packagist.org/packages/WyriHaximus/recoil-queue-caller)
[![PHP 7 ready](http://php7ready.timesplinter.ch/WyriHaximus/reactphp-http-middleware-clear-body/badge.svg)](https://travis-ci.org/WyriHaximus/reactphp-http-middleware-clear-body)

# Install

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require WyriHaximus/recoil-queue-caller
```

# Usage

The following example echo's `0123`

```php
$queueCaller = new QueueCaller($recoil);
$observable = observableFromArray(iterator_to_array((function () {
    $func = function ($a, $b) {
        echo $a + $b;
    };
    yield [$func, 0, 0];
    yield [$func, 0, 1];
    yield [$func, 1, 1];
    yield [$func, 1, 2];
})()));
$queueCaller->call($observable);

```

# License

The MIT License (MIT)

Copyright (c) 2018 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
