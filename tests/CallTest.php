<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Rx;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use React\Promise\Promise;
use WyriHaximus\Recoil\Call;

final class CallTest extends TestCase
{
    public function testGetters()
    {
        $function = function () {
        };
        $arguments = range(0, 10);
        $call = new Call($function, ...$arguments);

        self::assertSame($function, $call->getCallable());
        self::assertSame($arguments, $call->getArguments());
    }

    public function testDeferredSuccess()
    {
        $call = new Call(function () {
        });

        $loop = Factory::create();
        $loop->futureTick(function () use ($call) {
            $call->resolve(123);
        });

        $result = $this->await(new Promise(function ($resolve, $reject) use ($call) {
            $call->wait($resolve, $reject);
        }), $loop);
        self::assertSame(123, $result);
    }

    public function testDeferredFailure()
    {
        $call = new Call(function () {
        });

        $loop = Factory::create();
        $loop->futureTick(function () use ($call) {
            $call->reject(new \Exception('whoops!'));
        });

        $this->expectException(\Exception::class, 'whoops!');
        $this->await(new Promise(function ($resolve, $reject) use ($call) {
            $call->wait($resolve, $reject);
        }), $loop);
    }
}
