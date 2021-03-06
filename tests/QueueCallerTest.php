<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Recoil;

use function ApiClients\Tools\Rx\observableFromArray;
use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Recoil\React\ReactKernel;
use WyriHaximus\Recoil\Call;
use WyriHaximus\Recoil\QueueCaller;

/**
 * @internal
 */
final class QueueCallerTest extends TestCase
{
    public function testOne(): void
    {
        $i = 0;
        $array = [];
        foreach (\range(1, 1000) as $_) {
            $array[] = new Call(function () use (&$i): void {
                $i++;
            });
        }

        $loop = Factory::create();
        $recoil = ReactKernel::create($loop);
        $recoil->setExceptionHandler(function ($et): void {
            echo (string)$et;
        });
        $queueCaller = new QueueCaller($recoil);
        $queueCaller->call(observableFromArray($array));
        $loop->run();

        self::assertSame(1000, $i);
    }
}
