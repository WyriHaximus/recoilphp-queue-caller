<?php declare(strict_types=1);

namespace WyriHaximus\Tests\Recoil;

use ApiClients\Tools\TestUtilities\TestCase;
use React\EventLoop\Factory;
use Rx\React\Promise;
use WyriHaximus\Recoil\InvalidStateException;
use WyriHaximus\Recoil\State;

/**
 * @internal
 */
final class StateTest extends TestCase
{
    public function provideValidStates()
    {
        yield [State::CREATED];
        yield [State::STARTED];
        yield [State::WAITING];
        yield [State::BUSY];
        yield [State::DONE];
    }

    /**
     * @dataProvider provideValidStates
     */
    public function testValidState(int $providedState): void
    {
        $loop = Factory::create();
        $state = new State();

        $loop->futureTick(function () use ($state, $providedState): void {
            $state->onNext($providedState);
            $state->onCompleted();
        });

        self::assertSame($providedState, $this->await(Promise::fromObservable($state), $loop, 1));
        self::assertSame($providedState, $state->getState());
    }

    public function provideInvalidStates()
    {
        yield [-1];
        yield [null];
        yield [true];
        yield [false];
        yield ['string'];
    }

    /**
     * @dataProvider provideInvalidStates
     * @param mixed $providedState
     */
    public function testInvalidState($providedState): void
    {
        $loop = Factory::create();
        $state = new State();

        $loop->futureTick(function () use ($state, $providedState): void {
            $state->onNext($providedState);
            $state->onCompleted();
        });

        $this->expectException(InvalidStateException::class);
        $this->await(Promise::fromObservable($state), $loop, 1);
    }
}
