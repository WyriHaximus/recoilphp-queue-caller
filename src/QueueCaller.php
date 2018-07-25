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

    public function call(ObservableInterface $observable)
    {
        $this->kernel->execute(function () use ($observable) {
            yield;
            $observableWhile = observableWhile($observable);
            /** @var Call $call */
            while ($call = (yield $observableWhile->get())) {
                try {
                    $callable = $call->getCallable();
                    $arguments = $call->getArguments();
                    $value = yield $callable(...$arguments);
                    $call->resolve($value);
                } catch (\Throwable $et) {
                    $call->reject($et);
                } finally {
                    unset($callable, $arguments, $call);
                }
            }
        });
    }
}
