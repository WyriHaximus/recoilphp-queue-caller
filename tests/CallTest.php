<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Recoil\React\ReactKernel;
use WyriHaximus\Recoil\Call;
use WyriHaximus\Recoil\QueueCaller;
use function ApiClients\Tools\Rx\observableFromArray;

final class CallTest extends TestCase
{
    public function testGetters()
    {
        $function = function () {};
        $arguments = range(0, 10);
        $call = new Call($function, ...$arguments);

        self::assertSame($function, $call->getCallable());
        self::assertSame($arguments, $call->getArguments());
    }

    public function testDeferredSuccess()
    {
        $call = new Call(function () {});

        $loop = Factory::create();
        $loop->futureTick(function () use ($call) {
            $call->resolve(123);
        });

        $result = $this->await($call, $loop);
        self::assertSame(123, $result);
    }

    public function testDeferredFailure()
    {
        $call = new Call(function () {});

        $loop = Factory::create();
        $loop->futureTick(function () use ($call) {
            $call->reject(new \Exception('whoops!'));
        });

        $this->expectException(\Exception::class, 'whoops!');
        $this->await($call, $loop);
    }
}
