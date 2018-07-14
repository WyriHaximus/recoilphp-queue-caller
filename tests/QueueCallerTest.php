<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Recoil\React\ReactKernel;
use WyriHaximus\Recoil\QueueCaller;
use function ApiClients\Tools\Rx\observableFromArray;

final class QueueCallerTest extends TestCase
{
    public function testOne()
    {
        $i = 0;
        $array = [];
        foreach (range(1, 1000) as $_) {
            $array[] = [function () use (&$i) {
                $i++;
            }];
        }

        $loop = Factory::create();
        $recoil = ReactKernel::create($loop);
        $queueCaller = new QueueCaller($recoil);
        $queueCaller->call(observableFromArray($array));
        $loop->run();

        self::assertSame(1000, $i);
    }
}
