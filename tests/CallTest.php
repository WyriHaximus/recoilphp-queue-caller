<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Recoil;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use React\Promise\Promise;
use WyriHaximus\Recoil\Call;

/**
 * @internal
 */
final class CallTest extends TestCase
{
    public function testGetters(): void
    {
        $function = function (): void {
        };
        $arguments = \range(0, 10);
        $call = new Call($function, ...$arguments);

        self::assertSame($function, $call->getCallable());
        self::assertSame($arguments, $call->getArguments());
    }

    public function testDeferredSuccess(): void
    {
        $call = new Call(function (): void {
        });

        $loop = Factory::create();
        $loop->futureTick(function () use ($call): void {
            $call->resolve(123);
        });

        $result = $this->await(new Promise(function ($resolve, $reject) use ($call): void {
            $call->wait($resolve, $reject);
        }), $loop);
        self::assertSame(123, $result);
    }

    public function testDeferredFailure(): void
    {
        $call = new Call(function (): void {
        });

        $loop = Factory::create();
        $loop->futureTick(function () use ($call): void {
            $call->reject(new \Exception('whoops!'));
        });

        $this->expectException(\Exception::class, 'whoops!');
        $this->await(new Promise(function ($resolve, $reject) use ($call): void {
            $call->wait($resolve, $reject);
        }), $loop);
    }
}
