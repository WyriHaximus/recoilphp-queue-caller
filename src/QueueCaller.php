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
            while ($callableAndArguments = (yield $observableWhile->get())) {
                $callable = array_shift($callableAndArguments);
                $arguments = $callableAndArguments;
                unset($callableAndArguments);

                yield $callable(...$arguments);

                unset($callable, $arguments);
            }
        });
    }
}
