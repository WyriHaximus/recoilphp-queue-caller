<?php declare(strict_types=1);

namespace WyriHaximus\Recoil;

use Recoil\Kernel;
use Rx\ObservableInterface;
use function WyriHaximus\Rx\observableWhile;

final class QueueCaller
{
    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function call(ObservableInterface $observable): State
    {
        $state = new State();
        $this->kernel->execute(function () use ($observable, $state) {
            $state->onNext(State::STARTED);
            yield;
            $observableWhile = observableWhile($observable);
            $state->onNext(State::WAITING);
            /** @var Call $call */
            while ($call = (yield $observableWhile->get())) {
                try {
                    $state->onNext(State::BUSY);
                    $callable = $call->getCallable();
                    $arguments = $call->getArguments();
                    $value = yield $callable(...$arguments);
                    $call->resolve($value);
                } catch (\Throwable $et) {
                    $call->reject($et);
                } finally {
                    unset($callable, $arguments, $call);
                    $state->onNext(State::WAITING);
                }
            }
            $state->onNext(State::DONE);
        });

        return $state;
    }
}
