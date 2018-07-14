<?php

use React\EventLoop\Factory;
use Recoil\React\ReactKernel;
use function ApiClients\Tools\Rx\observableFromArray;
use WyriHaximus\Recoil\QueueCaller;
use function WyriHaximus\Rx\observableWhile;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$loop = Factory::create();

$recoil = ReactKernel::create($loop);
$queueCaller = new QueueCaller($recoil);
$observable = observableFromArray(iterator_to_array((function () {
    $func = function ($a, $b) {
        echo $a + $b;
    };
    yield [$func, 0, 0];
    yield [$func, 0, 1];
    yield [$func, 1, 1];
    yield [$func, 1, 2];
    yield [$func, 2, 2];
    yield [$func, 2, 3];
    yield [$func, 3, 3];
    yield [$func, 3, 4];
    yield [$func, 4, 4];
    yield [$func, 4, 5];
    yield [$func, 5, 5];
})()));
$queueCaller->call($observable);

$loop->run();
