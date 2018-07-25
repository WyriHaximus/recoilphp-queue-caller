<?php declare(strict_types=1);

namespace WyriHaximus\Recoil;

use React\Promise\Deferred;
use React\Promise\PromiseInterface;

final class Call implements PromiseInterface
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * @var mixed[]
     */
    private $arguments;

    /**
     * @var Deferred
     */
    private $deferred;

    /**
     * @param callable $callable
     * @param mixed[]  $arguments
     */
    public function __construct(callable $callable, ...$arguments)
    {
        $this->callable = $callable;
        $this->arguments = $arguments;
        $this->deferred = new Deferred();
    }

    /**
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * @return mixed[]
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    public function resolve($value): void
    {
        $this->deferred->resolve($value);
    }

    public function reject($value): void
    {
        $this->deferred->reject($value);
    }

    public function then(callable $onFulfilled = null, callable $onRejected = null, callable $onProgress = null): PromiseInterface
    {
        return $this->deferred->promise()->then($onFulfilled, $onRejected, $onProgress);
    }
}
